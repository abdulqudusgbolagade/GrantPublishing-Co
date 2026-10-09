"""Build new-install Elementor layouts from the maintained shortcode templates.

This development utility writes bundled JSON only. It never connects to WordPress
or rewrites an installed page's saved Elementor content.
"""
from pathlib import Path
from copy import deepcopy
from hashlib import sha256
import json
from lxml import html, etree

ROOT = Path(__file__).resolve().parents[1] / 'grant-publishing-site'
PAGES = json.loads((ROOT / 'pages.json').read_text())

def inner(element):
    return (element.text or '') + ''.join(etree.tostring(c, method='html', encoding='unicode') for c in element)

def build(element, page, path):
    css = element.get('class', '')
    settings = {'_title': css.replace('gp-', '').replace('-', ' ').title() or 'Content'}
    if element.get('id'):
        settings['_element_id'] = element.get('id')
    ident = sha256((page + ':' + path).encode()).hexdigest()[:8]
    node = {'id': ident, 'elType': 'widget', 'widgetType': 'text-editor', 'isInner': False,
            'settings': settings, 'elements': []}
    if css:
        settings['_css_classes'] = css
    children = list(element)
    heading = children[0] if len(children) == 1 and children[0].tag in ('h1', 'h2', 'h3') else None
    image = None
    link = None
    if len(children) == 1 and children[0].tag == 'img':
        image = children[0]
    elif len(children) == 1 and children[0].tag == 'a' and len(children[0]) == 1 and children[0][0].tag == 'img':
        link, image = children[0], children[0][0]
    if heading is not None:
        node['widgetType'] = 'heading'
        content = heading
        if len(heading) == 1 and heading[0].tag == 'a':
            content = heading[0]
            settings['link'] = {'url': content.get('href'), 'is_external': 'on' if content.get('target') == '_blank' else '', 'nofollow': ''}
        settings.update(title=inner(content).strip(), header_size=heading.tag)
    elif image is not None:
        node['widgetType'] = 'image'
        settings.update(image={'url': image.get('src'), 'id': 0, 'alt': image.get('alt', '')},
                        image_size='full', caption_source='none', link_to='none')
        if link is not None:
            settings['link_to'] = 'custom'
            settings['link'] = {'url': link.get('href'), 'is_external': 'on' if link.get('target') == '_blank' else '', 'nofollow': ''}
            if link.get('aria-label'):
                settings['link']['custom_attributes'] = 'aria-label|' + link.get('aria-label')
    elif len(children) == 1 and children[0].tag == 'a' and 'elementor-button' in children[0].get('class', '').split():
        node['widgetType'] = 'button'
        anchor = children[0]
        settings.update(text=anchor.text_content().strip(), link={'url': anchor.get('href'), 'is_external': 'on' if anchor.get('target') == '_blank' else '', 'nofollow': ''})
    elif not children and '{{form:' in (element.text or ''):
        node['widgetType'] = 'shortcode'
        settings['shortcode'] = '[grant_enquiry_form request="' + ('assessment' if '{{form:assessment}}' in inner(element) else 'project') + '"]'
    elif not children and '{{showcase:' in (element.text or ''):
        node['widgetType'] = 'shortcode'
        settings['shortcode'] = '[grant_book_showcase context="' + ('services' if '{{showcase:services}}' in inner(element) else 'home') + '"]'
    elif not children and '{{contact_links}}' in (element.text or ''):
        node['widgetType'] = 'shortcode'
        settings['shortcode'] = '[grant_contact_links]'
    elif element.tag == 'div' and children and all(c.tag == 'div' or (c.tag == 'ul' and c.get('class')) for c in children):
        node.pop('widgetType')
        node['elType'] = 'container'
        settings.pop('_css_classes', None)
        settings.update(css_classes=css, content_width='full', flex_direction='column',
                        padding={'unit': 'px', 'top': '0', 'right': '0', 'bottom': '0', 'left': '0', 'isLinked': True},
                        flex_gap={'unit': 'px', 'size': 0, 'column': '0', 'row': '0'})
        node['elements'] = [build(child, page, path + '/' + str(i)) for i, child in enumerate(children)]
    else:
        if element.tag == 'div':
            settings['editor'] = inner(element).strip()
        else:
            clone = deepcopy(element)
            clone.attrib.pop('class', None)
            clone.attrib.pop('id', None)
            settings['editor'] = etree.tostring(clone, method='html', encoding='unicode').strip()
    return node

if __name__ == '__main__':
    total = 0
    for key in PAGES:
        document = html.fromstring((ROOT / 'templates' / (key + '.html')).read_text())
        main = document.xpath('.//main')[0]
        target = ROOT / 'elementor' / (key + '.json')
        data = json.loads(target.read_text())
        data['content'] = [build(child, key, str(i)) for i, child in enumerate(main)]
        data['title'] = PAGES[key]['title']
        target.write_text(json.dumps(data, ensure_ascii=False, indent=2) + '\n')
        total += 1
    print(f'Built {total} bundled native layouts; installed saved pages remain untouched.')
