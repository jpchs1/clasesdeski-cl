#!/usr/bin/perl
# ClasesdeSki — Build dedicated SEO pages from /es/index.html
# Usage: perl build_seo_pages.pl <home_html_path> <output_dir>
use strict;
use warnings;
use utf8;
use Encode;

binmode(STDOUT, ":utf8");

my %PAGES = (
    blog => {
        title => 'Blog de Ski y Snowboard en Chile | CDSKI — Guías y Consejos',
        description => 'Guías, consejos y experiencias en los mejores centros de ski de Chile. Valle Nevado, El Colorado, La Parva. Aprende con CDSKI.',
        og_title => 'Blog CDSKI — Ski y Snowboard en los Andes Chilenos',
        canonical => 'https://clasesdeski.cl/es/blog/',
        anchor => 'blog',
    },
    faq => {
        title => 'Preguntas Frecuentes (FAQ) | CDSKI Clases de Ski Chile',
        description => 'Respuestas a las preguntas más frecuentes sobre nuestras clases de ski y snowboard en Valle Nevado, El Colorado y La Parva.',
        og_title => 'Preguntas Frecuentes — CDSKI Clases de Ski',
        canonical => 'https://clasesdeski.cl/es/faq/',
        anchor => 'faq',
    },
    servicios => {
        title => 'Servicios y Clases de Ski / Snowboard | CDSKI Chile',
        description => 'Clases grupales, privadas, heliski y programas para niños. Servicios all-inclusive en Valle Nevado, El Colorado y La Parva con instructores expertos.',
        og_title => 'Servicios CDSKI — Clases de Ski, Snowboard y Heliski en Chile',
        canonical => 'https://clasesdeski.cl/es/servicios/',
        anchor => 'services',
    },
    galeria => {
        title => 'Galería de Fotos | CDSKI Clases de Ski en los Andes Chilenos',
        description => 'Fotos y momentos de nuestras experiencias de ski y snowboard en Valle Nevado, El Colorado y La Parva. Mira a nuestros estudiantes en acción.',
        og_title => 'Galería CDSKI — Ski y Snowboard en Chile',
        canonical => 'https://clasesdeski.cl/es/galeria/',
        anchor => 'gallery',
    },
);

sub json_escape {
    my $s = shift;
    $s =~ s/\\/\\\\/g;
    $s =~ s/"/\\"/g;
    $s =~ s/\n/\\n/g;
    return $s;
}

sub make_seo_script {
    my $cfg = shift;
    my $title = json_escape($cfg->{title});
    my $desc = json_escape($cfg->{description});
    my $og_title = json_escape($cfg->{og_title});
    my $canonical = json_escape($cfg->{canonical});
    my $anchor = json_escape($cfg->{anchor});
    return qq{
<script>
(function(){
  var seo = {"title":"$title","description":"$desc","og_title":"$og_title","canonical":"$canonical","anchor":"$anchor"};
  function applySEO(){
    if (document.title !== seo.title) document.title = seo.title;
    var setMeta = function(sel, val){
      var el = document.querySelector(sel);
      if (el && el.getAttribute('content') !== val) el.setAttribute('content', val);
    };
    var setLink = function(sel, href){
      var el = document.querySelector(sel);
      if (el && el.href !== href) el.href = href;
    };
    setMeta('meta[name="description"]', seo.description);
    setLink('link[rel="canonical"]', seo.canonical);
    setMeta('meta[property="og:title"]', seo.og_title);
    setMeta('meta[property="og:url"]', seo.canonical);
    setMeta('meta[property="og:description"]', seo.description);
    setMeta('meta[name="twitter:title"]', seo.og_title);
    setMeta('meta[name="twitter:description"]', seo.description);
  }
  function scrollToSection(){
    var el = document.getElementById(seo.anchor);
    if (el) el.scrollIntoView({ behavior: 'instant', block: 'start' });
  }
  applySEO();
  if (document.readyState === 'complete') {
    setTimeout(applySEO, 50);
    setTimeout(applySEO, 300);
    setTimeout(applySEO, 1000);
    setTimeout(scrollToSection, 200);
  } else {
    window.addEventListener('load', function(){
      applySEO();
      setTimeout(applySEO, 50);
      setTimeout(applySEO, 300);
      setTimeout(applySEO, 1000);
      setTimeout(scrollToSection, 200);
    }, { once: true });
  }
  if (document.head) {
    new MutationObserver(applySEO).observe(document.head, { childList: true, subtree: true, characterData: true });
  }
})();
</script>};
}

sub escape_repl {
    my $s = shift;
    $s =~ s/\\/\\\\/g;
    $s =~ s/\$/\\\$/g;
    $s =~ s/\@/\\\@/g;
    return $s;
}

sub build_page {
    my ($base, $cfg) = @_;
    my $html = $base;
    my $t = escape_repl($cfg->{title});
    my $d = escape_repl($cfg->{description});
    my $ot = escape_repl($cfg->{og_title});
    my $c = escape_repl($cfg->{canonical});

    $html =~ s|<title>[^<]+</title>|<title>$t</title>|;
    $html =~ s|<meta name="description" content="[^"]+"/>|<meta name="description" content="$d"/>|;
    $html =~ s|<link rel="canonical" href="[^"]+"/>|<link rel="canonical" href="$c"/>|;
    $html =~ s|<meta property="og:title" content="[^"]+"/>|<meta property="og:title" content="$ot"/>|;
    $html =~ s|<meta property="og:url" content="[^"]+"/>|<meta property="og:url" content="$c"/>|;
    $html =~ s|<meta property="og:description" content="[^"]+"/>|<meta property="og:description" content="$d"/>|;
    $html =~ s|<meta name="twitter:title" content="[^"]+"/>|<meta name="twitter:title" content="$ot"/>|;
    $html =~ s|<meta name="twitter:description" content="[^"]+"/>|<meta name="twitter:description" content="$d"/>|;

    $html =~ s|(<script[^>]*src="/_next/static/chunks/[^"]+)\?v=\d+|$1|g;

    my $seo = make_seo_script($cfg);
    my $seo_esc = escape_repl($seo);
    $html =~ s|</body>|$seo_esc\n</body>|;

    return $html;
}

die "Usage: $0 <home_html_path> <output_dir>\n" unless @ARGV == 2;
my ($home_path, $out_base) = @ARGV;

open(my $fh, '<:encoding(UTF-8)', $home_path) or die "Cannot open $home_path: $!\n";
my $base = do { local $/; <$fh> };
close($fh);

my $size = length(Encode::encode_utf8($base));
print "Read home: $home_path ($size bytes)\n";

for my $slug (sort keys %PAGES) {
    my $cfg = $PAGES{$slug};
    my $html = build_page($base, $cfg);
    my $out_dir = "$out_base/$slug";
    mkdir $out_dir unless -d $out_dir;
    my $out_file = "$out_dir/index.html";
    open(my $out, '>:encoding(UTF-8)', $out_file) or die "Cannot write $out_file: $!\n";
    print $out $html;
    close($out);
    my $out_size = length(Encode::encode_utf8($html));
    print "  Built: $out_file ($out_size bytes)\n";
}
print "\nDone. " . scalar(keys %PAGES) . " pages built.\n";
