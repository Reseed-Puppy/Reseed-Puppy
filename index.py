from sanic.response import html, redirect
from config.site_config import sites
from log import writeLog
from sanic import Sanic
from seed import seed
import asyncio
import jinja2
app = Sanic(__name__)
jinja_env = jinja2.Environment(loader=jinja2.FileSystemLoader('templates'))
app.static('/static', './static')
site_config_file_path = 'config/site_config.py'
qb_config_file_path = 'config/qb_config.py'

@app.route('/')
async def index(request):
    existing_sites = []
    for site in sites:
        if (site['passkey'] != ''):
            existing_sites.append(site)
    template = jinja_env.get_template('index.html')
    html_content = template.render(sites=sites, existing_sites=existing_sites)
    return html(html_content)
async def reseed():
  while True:
    seed()
    await asyncio.sleep(600)
@app.route('/sitesubmit', methods=['POST'])
async def sitesubmit(request):
    site_id = request.form.get('site')
    passkey = request.form.get('passkey')
    for site in sites:
        if site['id'] == int(site_id):
            print('Updating passkey for ' + site['siteName'])
            site['passkey'] = passkey
    with open(site_config_file_path, 'w', encoding='utf-8') as f:
        f.write('sites =' + str(sites))
    return redirect('/')

@app.route('/qbsubmit', methods=['POST'])
async def qbsubmit(request):
    url = request.form.get('qburl')
    port = request.form.get('qbport')
    username = request.form.get('qbusername')
    password = request.form.get('qbpassword')
    config = {
        'url': url,
        'port': port,
        'username': username,
        'password': password
    }
    with open(qb_config_file_path, 'w') as f:
        f.write(f"qb = {config}")
    return redirect('/')
app.add_task(reseed())
if __name__ == '__main__':
    app.run(host='0.0.0.0', port=8000)
