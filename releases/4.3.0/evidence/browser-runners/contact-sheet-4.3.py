from pathlib import Path
from PIL import Image,ImageOps,ImageDraw
r=Path('/workspace/grant-qa/4.3-screens');import json;keys=list(json.loads(Path('/workspace/GrantPublishing-Co/grant-publishing-site/pages.json').read_text()))
for width in [1440,390]:
 tiles=[]
 for key in keys:
  img=Image.open(r/f'after-{key}-{width}.png').convert('RGB');img.thumbnail((260,1350))
  tile=Image.new('RGB',(280,1390),'#e7e2ed');tile.paste(img,((280-img.width)//2,34));ImageDraw.Draw(tile).text((12,10),f'{key} / {width}',fill='#09072b');tiles.append(tile)
 out=Image.new('RGB',(280*5,1390*4),'#d8d2de')
 for i,tile in enumerate(tiles):out.paste(tile,((i%5)*280,(i//5)*1390))
 out.save(r/f'contact-sheet-{width}.jpg',quality=90)
print('Two contact sheets for visual inspection; original captures retained.')
