#!/usr/bin/env python3
"""
ClasesdeSki — Build dedicated SEO pages from /es/index.html
Usage: python3 build_seo_pages.py <home_html_path> <output_dir>
Example: python3 build_seo_pages.py ~/clasesdeski.cl/es/index.html ~/clasesdeski.cl/es

Creates: /es/blog/index.html, /es/faq/index.html, /es/servicios/index.html, /es/galeria/index.html
Each is a clone of the home with:
  - Unique title, description, canonical, og:* meta tags
  - SEO-maintenance script that re-applies meta tags after React hydration
    (Next.js metadata API tries to revert; MutationObserver fights it back)
  - Auto-scroll to the relevant section after hydration
"""
import sys, re, os, json

PAGE_CONFIGS = {
    'blog': {
        'title': 'Blog de Ski y Snowboard en Chile | CDSKI — Guías y Consejos',
        'description': 'Guías, consejos y experiencias en los mejores centros de ski de Chile. Valle Nevado, El Colorado, La Parva. Aprende con CDSKI.',
        'og_title': 'Blog CDSKI — Ski y Snowboard en los Andes Chilenos',
        'canonical': 'https://clasesdeski.cl/es/blog/',
        'anchor': 'blog',
    },
    'faq': {
        'title': 'Preguntas Frecuentes (FAQ) | CDSKI Clases de Ski Chile',
        'description': 'Respuestas a las preguntas más frecuentes sobre nuestras clases de ski y snowboard en Valle Nevado, El Colorado y La Parva.',
        'og_title': 'Preguntas Frecuentes — CDSKI Clases de Ski',
        'canonical': 'https://clasesdeski.cl/es/faq/',
        'anchor': 'faq',
    },
    'servicios': {
        'title': 'Servicios y Clases de Ski / Snowboard | CDSKI Chile',
        'description': 'Clases grupales, privadas, heliski y programas para niños. Servicios all-inclusive en Valle Nevado, El Colorado y La Parva con instructores expertos.',
        'og_title': 'Servicios CDSKI — Clases de Ski, Snowboard y Heliski en Chile',
        'canonical': 'https://clasesdeski.cl/es/servicios/',
        'anchor': 'services',
    },
    'galeria': {
        'title': 'Galería de Fotos | CDSKI Clases de Ski en los Andes Chilenos',
        'description': 'Fotos y momentos de nuestras experiencias de ski y snowboard en Valle Nevado, El Colorado y La Parva. Mira a nuestros estudiantes en acción.',
        'og_title': 'Galería CDSKI — Ski y Snowboard en Chile',
        'canonical': 'https://clasesdeski.cl/es/galeria/',
        'anchor': 'gallery',
    },
}


def make_seo_script(cfg):
    seo_json = json.dumps({
        'title': cfg['title'],
        'description': cfg['description'],
        'og_title': cfg['og_title'],
        'canonical': cfg['canonical'],
        'anchor': cfg['anchor'],
    }, ensure_ascii=False)
    return f'''
<script>
(function(){{
  var seo = {seo_json};
  function applySEO(){{
    if (document.title !== seo.title) document.title = seo.title;
    var setMeta = function(sel, val){{
      var el = document.querySelector(sel);
      if (el && el.getAttribute('content') !== val) el.setAttribute('content', val);
    }};
    var setLink = function(sel, href){{
      var el = document.querySelector(sel);
      if (el && el.href !== href) el.href = href;
    }};
    setMeta('meta[name="description"]', seo.description);
    setLink('link[rel="canonical"]', seo.canonical);
    setMeta('meta[property="og:title"]', seo.og_title);
    setMeta('meta[property="og:url"]', seo.canonical);
    setMeta('meta[property="og:description"]', seo.description);
    setMeta('meta[name="twitter:title"]', seo.og_title);
    setMeta('meta[name="twitter:description"]', seo.description);
  }}
  function scrollToSection(){{
    var el = document.getElementById(seo.anchor);
    if (el) el.scrollIntoView({{ behavior: 'instant', block: 'start' }});
  }}
  applySEO();
  if (document.readyState === 'complete') {{
    setTimeout(applySEO, 50);
    setTimeout(applySEO, 300);
    setTimeout(applySEO, 1000);
    setTimeout(scrollToSection, 200);
  }} else {{
    window.addEventListener('load', function(){{
      applySEO();
      setTimeout(applySEO, 50);
      setTimeout(applySEO, 300);
      setTimeout(applySEO, 1000);
      setTimeout(scrollToSection, 200);
    }}, {{ once: true }});
  }}
  if (document.head) {{
    new MutationObserver(applySEO).observe(document.head, {{ childList: true, subtree: true, characterData: true }});
  }}
}})();
</script>'''


def build_page(base_html, cfg):
    html = base_html
    html = re.sub(r'<title>[^<]+</title>', f"<title>{cfg['title']}</title>", html, count=1)
    html = re.sub(r'<meta name="description" content="[^"]+"/>', f'<meta name="description" content="{cfg["description"]}"/>', html, count=1)
    html = re.sub(r'<link rel="canonical" href="[^"]+"/>', f'<link rel="canonical" href="{cfg["canonical"]}"/>', html, count=1)
    html = re.sub(r'<meta property="og:title" content="[^"]+"/>', f'<meta property="og:title" content="{cfg["og_title"]}"/>', html, count=1)
    html = re.sub(r'<meta property="og:url" content="[^"]+"/>', f'<meta property="og:url" content="{cfg["canonical"]}"/>', html, count=1)
    html = re.sub(r'<meta property="og:description" content="[^"]+"/>', f'<meta property="og:description" content="{cfg["description"]}"/>', html, count=1)
    html = re.sub(r'<meta name="twitter:title" content="[^"]+"/>', f'<meta name="twitter:title" content="{cfg["og_title"]}"/>', html, count=1)
    html = re.sub(r'<meta name="twitter:description" content="[^"]+"/>', f'<meta name="twitter:description" content="{cfg["description"]}"/>', html, count=1)
    html = html.replace('</body>', make_seo_script(cfg) + '\n</body>')
    html = re.sub(r'(<script[^>]*src="/_next/static/chunks/[^"]+)\?v=[0-9]+', r'\1', html)
    return html


def main():
    if len(sys.argv) != 3:
        print(f'Usage: {sys.argv[0]} <home_html_path> <output_dir>')
        sys.exit(1)
    home_path = sys.argv[1]
    out_base = sys.argv[2]
    with open(home_path, 'r', encoding='utf-8') as f:
        base = f.read()
    print(f'Read home: {home_path} ({len(base)} bytes)')
    for slug, cfg in PAGE_CONFIGS.items():
        html = build_page(base, cfg)
        out_dir = os.path.join(out_base, slug)
        os.makedirs(out_dir, exist_ok=True)
        out_file = os.path.join(out_dir, 'index.html')
        with open(out_file, 'w', encoding='utf-8') as f:
            f.write(html)
        print(f'  Built: {out_file} ({len(html)} bytes)')
    print(f'\nDone. {len(PAGE_CONFIGS)} pages built.')


if __name__ == '__main__':
    main()
