<?php
/**
 * Plugin Name: CDSKI Homepage Integration
 * Description: Integrated homepage sections, menu reorganization, and footer links for CDSKI Chile. Uses wp_head since this theme does not call wp_footer().
 * Version: 2.0
 * Author: CDSKI Dev
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// =====================================================================
// HOMEPAGE: Inject integrated sections via wp_head (JS on DOMContentLoaded)
// This theme does NOT call wp_footer(), so all injection goes through wp_head.
// =====================================================================
add_action( 'wp_head', 'cdski_mu_homepage_head_injection', 99 );
function cdski_mu_homepage_head_injection() {
    if ( ! is_front_page() && ! is_home() ) {
        return;
    }
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function(){

        var discoverHTML = '<section id="cdski-discover-cards" class="cdski-hp-discover" style="opacity:0;transition:opacity .5s ease;" aria-label="Descubre CDSKI">'
            + '<div class="content_wrap"><div class="cdski-hp-discover-header">'
            + '<span class="cdski-hp-label">DESCUBRE CDSKI</span>'
            + '<h2>M\u00e1s que Clases: Una Experiencia Completa</h2>'
            + '<p>Conoce nuestra filosof\u00eda, metodolog\u00eda y todo lo que hace \u00fanica la experiencia CDSKI en la monta\u00f1a.</p>'
            + '</div><div class="cdski-hp-cards-grid">'
            + '<a href="/about-cdski/" class="cdski-hp-card"><span class="cdski-hp-card-icon">\u2605</span><h3>Sobre CDSKI</h3><p>Filosof\u00eda, valores y manifiesto de nuestra escuela</p><span class="cdski-hp-card-arrow">\u2192</span></a>'
            + '<a href="/nuestro-metodo/" class="cdski-hp-card"><span class="cdski-hp-card-icon">\u25C6</span><h3>Nuestro M\u00e9todo</h3><p>C\u00f3mo ense\u00f1amos ski y snowboard de forma entretenida</p><span class="cdski-hp-card-arrow">\u2192</span></a>'
            + '<a href="/niveles/" class="cdski-hp-card"><span class="cdski-hp-card-icon">\u25B2</span><h3>Niveles de Progreso</h3><p>Los 7 niveles del alumno CDSKI</p><span class="cdski-hp-card-arrow">\u2192</span></a>'
            + '<a href="/experiencia-cdski/" class="cdski-hp-card"><span class="cdski-hp-card-icon">\u2744</span><h3>La Experiencia CDSKI</h3><p>Experiencias guiadas personalizadas en la monta\u00f1a</p><span class="cdski-hp-card-arrow">\u2192</span></a>'
            + '<a href="/clases-ski-snowboard/" class="cdski-hp-card"><span class="cdski-hp-card-icon">\u2733</span><h3>Clases de Ski y Snowboard</h3><p>Clases privadas, grupales y d\u00edas guiados</p><span class="cdski-hp-card-arrow">\u2192</span></a>'
            + '<a href="/seguridad-montana/" class="cdski-hp-card"><span class="cdski-hp-card-icon">\u26F0</span><h3>Seguridad y Monta\u00f1a</h3><p>C\u00f3digo del esquiador y cultura de monta\u00f1a</p><span class="cdski-hp-card-arrow">\u2192</span></a>'
            + '</div></div></section>';

        var expCtaHTML = '<section id="cdski-experience-cta" class="cdski-hp-exp-cta" style="opacity:0;transition:opacity .5s ease;" aria-label="Experiencia CDSKI">'
            + '<div class="content_wrap"><div class="cdski-hp-exp-inner">'
            + '<div class="cdski-hp-exp-text"><h2>Vive la Experiencia CDSKI en la Monta\u00f1a</h2>'
            + '<p>\u201CNos divertimos y entretenemos mientras aprendemos.\u201D Cada clase es una aventura personalizada con instructores expertos en Valle Nevado, Colorado y La Parva.</p></div>'
            + '<div class="cdski-hp-exp-actions">'
            + '<a href="/clases-ski-snowboard/" class="cdski-btn cdski-btn-primary">Reserva tu Clase</a>'
            + '<a href="https://api.whatsapp.com/send?phone=56940211459&text=Hola%20CDSKI!" class="cdski-btn cdski-btn-whatsapp">WhatsApp</a>'
            + '</div></div></div></section>';

        var prefooterHTML = '<section id="cdski-prefooter" class="cdski-hp-prefooter" style="opacity:0;transition:opacity .5s ease;" aria-label="CDSKI Chile">'
            + '<div class="content_wrap"><div class="cdski-hp-prefooter-grid">'
            + '<div class="cdski-hp-prefooter-about"><h3>CDSKI Chile</h3>'
            + '<p>Experiencia de Gu\u00eda & Clases de Ski y Snowboard en Valle Nevado, Colorado y La Parva. Aprende ski o snowboard con instructores expertos en los Andes chilenos.</p>'
            + '<p>\u201CNos divertimos y entretenemos mientras aprendemos.\u201D</p>'
            + '<div class="cdski-hp-prefooter-ctas"><a href="/clases-ski-snowboard/" class="cdski-btn cdski-btn-outline">Ver Clases</a>'
            + '<a href="/experiencia-cdski/" class="cdski-btn cdski-btn-outline">La Experiencia</a></div></div>'
            + '<div class="cdski-hp-prefooter-links"><h4>Explora CDSKI</h4><ul>'
            + '<li><a href="/about-cdski/">Sobre CDSKI Chile</a></li>'
            + '<li><a href="/nuestro-metodo/">Nuestro M\u00e9todo</a></li>'
            + '<li><a href="/niveles/">Niveles de Progreso</a></li>'
            + '<li><a href="/experiencia-cdski/">La Experiencia CDSKI</a></li>'
            + '<li><a href="/clases-ski-snowboard/">Clases de Ski y Snowboard</a></li>'
            + '<li><a href="/seguridad-montana/">Seguridad y Monta\u00f1a</a></li>'
            + '</ul></div>'
            + '<div class="cdski-hp-prefooter-seo"><h4>Clases y Experiencias de Ski en Chile</h4>'
            + '<p>CDSKI ofrece <a href="/clases-ski-snowboard/">clases de ski en Chile</a>, <a href="/clases-ski-snowboard/">clases de snowboard en Chile</a>, <a href="/clases-ski-snowboard/">clases de ski en Valle Nevado</a>, y <a href="/experiencia-cdski/">experiencias guiadas de ski</a> en los principales centros de ski de la Regi\u00f3n Metropolitana. Nuestros <a href="/about-cdski/">instructores expertos</a> ofrecen clases privadas, grupales y experiencias de gu\u00eda de monta\u00f1a personalizadas.</p>'
            + '</div></div></div></section>';

        function htmlToElement(html) {
            var t = document.createElement('div');
            t.innerHTML = html.trim();
            return t.firstChild;
        }

        var discover  = htmlToElement(discoverHTML);
        var expCta    = htmlToElement(expCtaHTML);
        var prefooter = htmlToElement(prefooterHTML);

        function findRowByText(container, searchText) {
            if (!container) return null;
            var kids = container.children;
            for (var i = 0; i < kids.length; i++) {
                if (kids[i].textContent && kids[i].textContent.substring(0, 120).indexOf(searchText) !== -1) return kids[i];
            }
            return null;
        }

        var container = null;
        var allDivs = document.querySelectorAll('article div');
        for (var i = 0; i < allDivs.length; i++) {
            for (var n = 0; n < allDivs[i].childNodes.length; n++) {
                if (allDivs[i].childNodes[n].nodeType === 3 &&
                    allDivs[i].childNodes[n].textContent.trim().indexOf('QUE OFRECEMOS') !== -1) {
                    var el = allDivs[i];
                    for (var lvl = 0; lvl < 10; lvl++) {
                        var p = el.parentElement;
                        if (!p) break;
                        if (p.children.length > 10) { container = p; break; }
                        el = p;
                    }
                    break;
                }
            }
            if (container) break;
        }

        if (container) {
            var queRow = findRowByText(container, 'QUE OFRECEMOS');
            if (queRow && queRow.nextSibling) {
                container.insertBefore(discover, queRow.nextSibling);
            }
            var bienRow = findRowByText(container, 'Bienvenidos a');
            if (bienRow && bienRow.nextSibling) {
                container.insertBefore(expCta, bienRow.nextSibling);
            }
        } else {
            var article = document.querySelector('article');
            if (article) {
                article.appendChild(discover);
                article.appendChild(expCta);
            }
        }

        var footer = document.querySelector('footer');
        if (footer && footer.parentNode) {
            footer.parentNode.insertBefore(prefooter, footer);
        }

        setTimeout(function(){
            discover.style.opacity  = '1';
            expCta.style.opacity    = '1';
            prefooter.style.opacity = '1';
        }, 100);

        /* Menu reorganization */
        var cdskiPages = [
            {href: '/about-cdski/',          text: 'Sobre CDSKI'},
            {href: '/nuestro-metodo/',       text: 'Nuestro M\u00e9todo'},
            {href: '/niveles/',              text: 'Niveles'},
            {href: '/experiencia-cdski/',     text: 'Experiencia CDSKI'},
            {href: '/clases-ski-snowboard/', text: 'Clases Ski & Snowboard'},
            {href: '/seguridad-montana/',    text: 'Seguridad'}
        ];
        var slugs = ['/about-cdski/','/nuestro-metodo/','/niveles/',
                     '/experiencia-cdski/','/clases-ski-snowboard/','/seguridad-montana/'];

        var mainNav = document.querySelector('header nav > ul');
        if (mainNav) {
            var items = mainNav.querySelectorAll(':scope > li');
            for (var i = 0; i < items.length; i++) {
                var a = items[i].querySelector('a');
                if (a) { try { var path = new URL(a.href, location.origin).pathname; if (slugs.indexOf(path) !== -1) items[i].style.display = 'none'; } catch(e) {} }
            }

            var nosotrosLi = null, blogLi = null;
            items = mainNav.querySelectorAll(':scope > li');
            for (var j = 0; j < items.length; j++) {
                var link = items[j].querySelector('a');
                if (!link) continue;
                var txt = link.textContent.trim().toLowerCase();
                if (txt === 'nosotros') nosotrosLi = items[j];
                if (txt === 'blog') blogLi = items[j];
            }

            var newLi = document.createElement('li');
            newLi.className = 'menu-item menu-item-has-children cdski-mega-menu';
            var newA = document.createElement('a');
            newA.href = '/about-cdski/';
            newA.textContent = 'CDSKI';
            newLi.appendChild(newA);

            var subUl = document.createElement('ul');
            subUl.className = 'sub-menu cdski-dropdown';
            for (var k = 0; k < cdskiPages.length; k++) {
                var li = document.createElement('li');
                li.className = 'menu-item';
                var la = document.createElement('a');
                la.href = cdskiPages[k].href;
                la.textContent = cdskiPages[k].text;
                li.appendChild(la);
                subUl.appendChild(li);
            }
            newLi.appendChild(subUl);

            if (nosotrosLi && nosotrosLi.nextSibling) mainNav.insertBefore(newLi, nosotrosLi.nextSibling);
            else if (blogLi) mainNav.insertBefore(newLi, blogLi);
            else mainNav.appendChild(newLi);
        }

        /* Footer links */
        var footerBar = document.querySelector('.copyright_wrap ul');
        if (!footerBar) footerBar = document.querySelector('footer + div ul');
        if (!footerBar) {
            var fDivs = document.querySelectorAll('footer ~ div');
            for (var d = 0; d < fDivs.length; d++) {
                var ul = fDivs[d].querySelector('ul');
                if (ul && fDivs[d].textContent.indexOf('Reservados') !== -1) { footerBar = ul; break; }
            }
        }
        if (footerBar) {
            var fl = [{href:'/about-cdski/',text:'Sobre CDSKI'},{href:'/nuestro-metodo/',text:'Nuestro M\u00e9todo'},{href:'/experiencia-cdski/',text:'Experiencia'},{href:'/clases-ski-snowboard/',text:'Clases'},{href:'/seguridad-montana/',text:'Seguridad'}];
            for (var f = 0; f < fl.length; f++) {
                var fli = document.createElement('li');
                fli.className = 'menu-item cdski-footer-item';
                var fa = document.createElement('a');
                fa.href = fl[f].href;
                fa.textContent = fl[f].text;
                fa.style.cssText = 'color:#999;text-decoration:none;font-size:13px;';
                fli.appendChild(fa);
                footerBar.appendChild(fli);
            }
        }
    });
    </script>
    <?php
}

// =====================================================================
// ALL PAGES: Menu reorganization (runs on every page except homepage)
// =====================================================================
add_action( 'wp_head', 'cdski_mu_menu_all_pages', 99 );
function cdski_mu_menu_all_pages() {
    if ( is_front_page() || is_home() ) {
        return;
    }
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function(){
        var cdskiPages = [
            {href: '/about-cdski/',          text: 'Sobre CDSKI'},
            {href: '/nuestro-metodo/',       text: 'Nuestro M\u00e9todo'},
            {href: '/niveles/',              text: 'Niveles'},
            {href: '/experiencia-cdski/',     text: 'Experiencia CDSKI'},
            {href: '/clases-ski-snowboard/', text: 'Clases Ski & Snowboard'},
            {href: '/seguridad-montana/',    text: 'Seguridad'}
        ];
        var slugs = ['/about-cdski/','/nuestro-metodo/','/niveles/',
                     '/experiencia-cdski/','/clases-ski-snowboard/','/seguridad-montana/'];

        var mainNav = document.querySelector('header nav > ul');
        if (!mainNav) return;

        var items = mainNav.querySelectorAll(':scope > li');
        for (var i = 0; i < items.length; i++) {
            var a = items[i].querySelector('a');
            if (a) { try { var path = new URL(a.href, location.origin).pathname; if (slugs.indexOf(path) !== -1) items[i].style.display = 'none'; } catch(e) {} }
        }

        var nosotrosLi = null, blogLi = null;
        items = mainNav.querySelectorAll(':scope > li');
        for (var j = 0; j < items.length; j++) {
            var link = items[j].querySelector('a');
            if (!link) continue;
            var txt = link.textContent.trim().toLowerCase();
            if (txt === 'nosotros') nosotrosLi = items[j];
            if (txt === 'blog') blogLi = items[j];
        }

        var newLi = document.createElement('li');
        newLi.className = 'menu-item menu-item-has-children cdski-mega-menu';
        var newA = document.createElement('a');
        newA.href = '/about-cdski/';
        newA.textContent = 'CDSKI';
        newLi.appendChild(newA);

        var subUl = document.createElement('ul');
        subUl.className = 'sub-menu cdski-dropdown';
        for (var k = 0; k < cdskiPages.length; k++) {
            var li = document.createElement('li');
            li.className = 'menu-item';
            var la = document.createElement('a');
            la.href = cdskiPages[k].href;
            la.textContent = cdskiPages[k].text;
            li.appendChild(la);
            subUl.appendChild(li);
        }
        newLi.appendChild(subUl);

        if (nosotrosLi && nosotrosLi.nextSibling) mainNav.insertBefore(newLi, nosotrosLi.nextSibling);
        else if (blogLi) mainNav.insertBefore(newLi, blogLi);
        else mainNav.appendChild(newLi);
    });
    </script>
    <?php
}

// =====================================================================
// CONTENT PAGES: Internal navigation (via wp_head)
// =====================================================================
add_action( 'wp_head', 'cdski_mu_internal_nav_head', 99 );
function cdski_mu_internal_nav_head() {
    if ( ! is_page( array( 'about-cdski', 'nuestro-metodo', 'niveles', 'experiencia-cdski', 'clases-ski-snowboard', 'seguridad-montana' ) ) ) {
        return;
    }
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function(){
        var navHTML = '<nav class="cdski-internal-nav" aria-label="P\u00e1ginas CDSKI">'
            + '<div class="content_wrap"><h3>Explora CDSKI</h3>'
            + '<div class="cdski-internal-nav-grid">'
            + '<a href="/about-cdski/" class="cdski-nav-link">Sobre CDSKI</a>'
            + '<a href="/nuestro-metodo/" class="cdski-nav-link">Nuestro M\u00e9todo</a>'
            + '<a href="/niveles/" class="cdski-nav-link">Niveles de Progreso</a>'
            + '<a href="/experiencia-cdski/" class="cdski-nav-link">La Experiencia CDSKI</a>'
            + '<a href="/clases-ski-snowboard/" class="cdski-nav-link">Clases de Ski y Snowboard</a>'
            + '<a href="/seguridad-montana/" class="cdski-nav-link">Seguridad y Monta\u00f1a</a>'
            + '</div></div></nav>';
        var footer = document.querySelector('footer');
        if (footer && footer.parentNode) {
            var t = document.createElement('div');
            t.innerHTML = navHTML;
            footer.parentNode.insertBefore(t.firstChild, footer);
        }
    });
    </script>
    <?php
}
