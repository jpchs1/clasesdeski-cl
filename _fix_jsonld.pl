#!/usr/bin/perl
# ClasesdeSki — fix Rich Results JSON-LD across all index.html files.
# Replaces LocalBusiness / Course / BreadcrumbList with enriched + i18n'd
# versions. Preserves the FAQPage block (already well-localized).
# Usage: perl fix_jsonld.pl <root>
# Example: perl fix_jsonld.pl ~/clasesdeski.cl
use strict;
use warnings;
use utf8;
use Encode;
use JSON::PP;

binmode(STDOUT, ":utf8");

my $ROOT = $ARGV[0] || die "Usage: $0 <root>\n";
$ROOT =~ s{/$}{};

my @FILES = (
    [ "index.html",            "es" ],   # root home → Spanish
    [ "es/index.html",         "es" ],
    [ "en/index.html",         "en" ],
    [ "pt/index.html",         "pt" ],
    [ "es/servicios/index.html", "es" ],
    [ "es/faq/index.html",     "es" ],
    [ "es/galeria/index.html", "es" ],
    [ "es/blog/index.html",    "es" ],
);

my %I18N = (
    es => {
        name        => "CDSKI — Clases de Ski y Snowboard Chile",
        description => "Escuela premium de ski y snowboard en los Andes chilenos. Clases personalizadas para todos los niveles en Valle Nevado, El Colorado y La Parva. Instructores certificados bilingües, paquetes all-inclusive con transporte y hospedaje desde Santiago.",
        region      => "Región Metropolitana de Santiago",
        course_name => "Clases de Ski y Snowboard",
        course_desc => "Clases profesionales de ski y snowboard para todos los niveles en Valle Nevado, El Colorado y La Parva con instructores certificados bilingües.",
        offer_cat   => "Paid",
        breadcrumb_home => "Inicio",
    },
    en => {
        name        => "CDSKI — Ski & Snowboard Lessons Chile",
        description => "Premium ski and snowboard school in the Chilean Andes. Personalized lessons for all levels at Valle Nevado, El Colorado and La Parva. Certified bilingual instructors, all-inclusive packages with transport and lodging from Santiago.",
        region      => "Santiago Metropolitan Region",
        course_name => "Ski & Snowboard Lessons",
        course_desc => "Professional ski and snowboard lessons for all levels at Valle Nevado, El Colorado and La Parva with certified bilingual instructors.",
        offer_cat   => "Paid",
        breadcrumb_home => "Home",
    },
    pt => {
        name        => "CDSKI — Aulas de Ski e Snowboard Chile",
        description => "Escola premium de ski e snowboard nos Andes chilenos. Aulas personalizadas para todos os níveis em Valle Nevado, El Colorado e La Parva. Instrutores certificados bilíngues, pacotes all-inclusive com transporte e hospedagem desde Santiago.",
        region      => "Região Metropolitana de Santiago",
        course_name => "Aulas de Ski e Snowboard",
        course_desc => "Aulas profissionais de ski e snowboard para todos os níveis em Valle Nevado, El Colorado e La Parva com instrutores certificados bilíngues.",
        offer_cat   => "Paid",
        breadcrumb_home => "Início",
    },
);

sub build_localbusiness {
    my ($lang, $page_url) = @_;
    my $t = $I18N{$lang};
    return {
        '@type'              => [ "LocalBusiness", "SportsActivityLocation" ],
        '@id'                => "https://clasesdeski.cl/#business",
        'name'               => $t->{name},
        'alternateName'      => [ "CDSKI", "CDSKI Chile", "Clases de Ski Chile" ],
        'description'        => $t->{description},
        'url'                => "https://clasesdeski.cl",
        'telephone'          => "+56940211459",
        'email'              => "info\@clasesdeski.cl",
        'image'              => "https://clasesdeski.cl/og-image.jpg",
        'logo'               => "https://clasesdeski.cl/images/logo-cdski.png",
        'priceRange'         => "\$\$",
        'currenciesAccepted' => "CLP, USD, BRL, EUR",
        'paymentAccepted'    => "Cash, Credit Card, Bank Transfer, WhatsApp",
        'sport'              => [ "Skiing", "Snowboarding" ],
        'address' => {
            '@type'           => "PostalAddress",
            'streetAddress'   => "Mall Sport",
            'addressLocality' => "Las Condes",
            'addressRegion'   => $t->{region},
            'postalCode'      => "7550000",
            'addressCountry'  => "CL",
        },
        'geo' => {
            '@type'    => "GeoCoordinates",
            'latitude' => "-33.4028",
            'longitude'=> "-70.5756",
        },
        'openingHoursSpecification' => [
            {
                '@type'     => "OpeningHoursSpecification",
                'dayOfWeek' => [
                    "Monday", "Tuesday", "Wednesday", "Thursday",
                    "Friday", "Saturday", "Sunday"
                ],
                'opens'  => "08:00",
                'closes' => "22:00",
            }
        ],
        'areaServed' => [
            { '@type' => "Place",              'name' => "Valle Nevado, Chile" },
            { '@type' => "Place",              'name' => "El Colorado, Chile" },
            { '@type' => "Place",              'name' => "La Parva, Chile" },
            { '@type' => "AdministrativeArea", 'name' => "Santiago, Chile" },
            { '@type' => "Country",            'name' => "Chile" },
            { '@type' => "Country",            'name' => "Brazil" },
            { '@type' => "Country",            'name' => "United States" },
            { '@type' => "Country",            'name' => "Argentina" },
        ],
        'knowsLanguage' => [ "es", "en", "pt" ],
        'aggregateRating' => {
            '@type'       => "AggregateRating",
            'ratingValue' => "5.0",
            'reviewCount' => "70",
            'bestRating'  => "5",
        },
        'sameAs' => [
            "https://www.facebook.com/clasesdeski",
            "https://www.instagram.com/clasesdeski",
        ],
    };
}

sub build_course {
    my ($lang) = @_;
    my $t = $I18N{$lang};
    my $offers = [{
        '@type'         => "Offer",
        'category'      => $t->{offer_cat},
        'price'         => "60000",
        'priceCurrency' => "CLP",
        'availability'  => "https://schema.org/InStock",
        'url'           => "https://clasesdeski.cl/#pricing",
        'validFrom'     => "2026-06-01",
    }];
    return {
        '@type'           => "Course",
        'name'            => $t->{course_name},
        'description'     => $t->{course_desc},
        'inLanguage'      => $lang,
        'educationalLevel'=> "All levels / Todos los niveles",
        'coursePrerequisites' => "None",
        'provider' => {
            '@type' => "Organization",
            '@id'   => "https://clasesdeski.cl/#business",
            'name'  => "CDSKI",
            'url'   => "https://clasesdeski.cl",
        },
        'offers' => $offers,
        'hasCourseInstance' => [{
            '@type'          => "CourseInstance",
            'courseMode'     => "onsite",
            'courseWorkload' => "PT2H",
            'inLanguage'     => $lang,
            'location' => {
                '@type'   => "Place",
                'name'    => "Valle Nevado Ski Resort",
                'address' => {
                    '@type'           => "PostalAddress",
                    'addressLocality' => "Lo Barnechea",
                    'addressRegion'   => $t->{region},
                    'addressCountry'  => "CL",
                },
            },
            'offers' => $offers,
        }],
    };
}

sub build_breadcrumb {
    my ($lang, $page_url) = @_;
    my $t = $I18N{$lang};
    return {
        '@type'           => "BreadcrumbList",
        'itemListElement' => [
            {
                '@type'    => "ListItem",
                'position' => 1,
                'name'     => $t->{breadcrumb_home},
                'item'     => $page_url,
            }
        ],
    };
}

sub process_file {
    my ($rel, $lang) = @_;
    my $path = "$ROOT/$rel";
    unless (-f $path) {
        print "  ⚠ MISS $rel\n";
        return;
    }

    open(my $fh, '<:encoding(UTF-8)', $path) or die "Cannot read $path: $!";
    my $html = do { local $/; <$fh> };
    close($fh);

    my $page_url = "https://clasesdeski.cl/" . ($rel eq "index.html" ? "" : $rel);
    $page_url =~ s{index\.html$}{};

    # Find the FIRST <script type="application/ld+json"> ... </script> with @graph
    if ($html !~ m{(<script[^>]+type="application/ld\+json"[^>]*>)(\{"\@context":"https://schema\.org","\@graph":\[.*?\]\})(</script>)}s) {
        print "  ✗ NO MATCH $rel\n";
        return;
    }
    my $open  = $1;
    my $jsonl = $2;
    my $close = $3;

    # Parse the JSON
    my $data = eval { decode_json(encode_utf8($jsonl)) };
    if (!$data) {
        print "  ✗ JSON PARSE FAIL $rel: $@\n";
        return;
    }

    # Extract FAQPage from the graph (keep) and discard the rest
    my @graph = @{ $data->{'@graph'} };
    my ($faq) = grep { ($_->{'@type'} // '') eq 'FAQPage' } @graph;

    # Build new graph with enriched/i18n'd entries + preserved FAQPage
    my @new_graph = (
        build_localbusiness($lang, $page_url),
        build_course($lang),
        ($faq ? ($faq) : ()),
        build_breadcrumb($lang, $page_url),
    );

    my $new_data = {
        '@context' => "https://schema.org",
        '@graph'   => \@new_graph,
    };

    # Serialize compact, UTF-8, no escaping of slashes / unicode
    my $json = JSON::PP->new->utf8(0)->canonical(0)->allow_nonref(1)
                       ->convert_blessed(0)->ascii(0);
    my $new_jsonl = $json->encode($new_data);

    # Replace in HTML (replace the FIRST occurrence)
    my $old_block = quotemeta($open . $jsonl . $close);
    my $new_block = $open . $new_jsonl . $close;
    $html =~ s/$old_block/$new_block/;

    # Backup + write
    my $bak = "$path.bak-$$";
    rename($path, $bak) or die "Cannot backup $path: $!";
    open(my $out, '>:encoding(UTF-8)', $path) or die "Cannot write $path: $!";
    print $out $html;
    close($out);

    my $new_size = length(Encode::encode_utf8($jsonl));
    my $new_size2 = length(Encode::encode_utf8($new_jsonl));
    my $faq_count = $faq ? scalar(@{ $faq->{mainEntity} // [] }) : 0;
    print "  ✓ $rel  lang=$lang  jsonld: ${new_size}b → ${new_size2}b  (FAQ kept: $faq_count Qs)\n";
    print "    backup: $bak\n";
}

print "Fixing JSON-LD in $ROOT ...\n";
for my $row (@FILES) {
    process_file($row->[0], $row->[1]);
}
print "Done.\n";
