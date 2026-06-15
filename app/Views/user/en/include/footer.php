<?php
/** 
 * @var object $config_info
 */
?>

            </div>
        </main>

        <!-- Sticky Footer -->
        <footer class="footer mt-auto py-3 bg-body-tertiary">
            <div class="container">
                <span class="text-body"><?= $config_info->title ?> | </span>
                <span class="text-body-secondary">Tel : </span>
                <span class="text-body"><?= $config_info->phone ?> | </span>
                <span class="text-body-secondary">Email : </span>
                <span class="text-body"><?= $config_info->email ?> | </span>
                <span class="text-body-secondary">Address : </span>
                <span class="text-body">[<?= $config_info->post_code ?>] <?= $config_info->addr1 ?> <?= $config_info->addr2 ?></span>
            </div>
        </footer>
    </body>
</html>