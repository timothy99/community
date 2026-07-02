<?php
/**
 * @var object $config_info
 * @var array $menu_list
 * @var array $language_list
 * @var string $selected_language
 * @var string $autotranslate_yn
*/
?>

            </div>
        </main>

        <!-- Sticky Footer -->
        <footer class="footer mt-auto py-3 bg-body-tertiary">
            <div class="container">
                <span class="text-body"><?= $config_info->title ?> | </span>
                <span class="text-body-secondary">전화 : </span>
                <span class="text-body"><?= $config_info->phone ?> | </span>
                <span class="text-body-secondary">메일 : </span>
                <span class="text-body"><?= $config_info->email ?> | </span>
                <span class="text-body-secondary">주소 : </span>
                <span class="text-body">[<?= $config_info->post_code ?>] <?= $config_info->addr1 ?> <?= $config_info->addr2 ?></span>
            </div>
        </footer>
    </body>
</html>

<?php   if ($autotranslate_yn == 'Y') { ?>
<script>
    // 자동번역 대상 언어인 경우, 페이지 로드 시 자동으로 번역 실시
    (function() {
        var pathSegments = window.location.pathname.split('/').filter(Boolean);
        var targetLang = pathSegments[0];

        var langMap = {
            'en': 'en',
            'ja': 'ja',
            'zh': 'zh-CN'
        };

        var translationLang = langMap[targetLang];
        if (!translationLang) return;

        // Google Translate 위젯 로드
        var s = document.createElement('script');
        s.src = '//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
        document.head.appendChild(s);
    })();
</script>

<script>
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'ko',
            includedLanguages: 'en,ja,zh-CN',
            autoDisplay: false
        }, 'google_translate_element');
    }
</script>
<?php   } ?>
