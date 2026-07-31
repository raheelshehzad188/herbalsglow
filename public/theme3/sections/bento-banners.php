    <div class="hp-bento-banners" data-banner-count="<?php echo $sliderCount; ?>" style="display:none;">
        <div class="hp-bento-container">
                <div class="hp-banner-left-arrow" role="button" tabindex="0" aria-label="left button">
                    <i class="icon-recentlyviewedarrowleft"></i>
                </div>
                <div class="hp-banner-right-arrow" role="button" tabindex="0" aria-label="right button">
                    <i class="icon-recentlyviewedarrowright"></i>
                </div>
            <div class="hp-bento-clip">
                <style>
                    .hp-bento-banners .hp-bento-banner .cms-text-banner-wrapper .cms-banner-wrapper-root-link{display:block;width:100%;}
                    .hp-bento-banners .hp-bento-banner .cms-text-banner{text-align:initial;width:100%;height:auto;overflow:hidden;box-sizing:border-box;}
                    .hp-bento-banners .hp-bento-banner .cms-text-banner div{box-sizing:border-box;}
                    .hp-bento-banners .hp-bento-banner .cms-text-banner-wrapper a{text-decoration:none;color:unset;}
                    .hp-bento-banners .hp-bento-banner .cms-text-banner-wrapper a:hover{text-decoration:none;}
                    .hp-bento-banners .hp-bento-banner .cms-banner-wrapper-root{box-sizing:border-box;width:100%;margin:auto;position:relative;overflow:hidden;background-size:cover;background-position:center;background-repeat:no-repeat;justify-content:flex-start;align-items:flex-start;display:flex;padding-inline-start:18px;padding-block-start:14px;width:332px;height:249px;aspect-ratio:332/249;}
                    .hp-bento-banners[data-banner-count="2"] .hp-bento-grid{grid-auto-columns:332px;}
                    .hp-bento-banners[data-banner-count="3"] .hp-bento-grid{grid-auto-columns:332px;}
                    .hp-bento-banners[data-banner-count="5"] .hp-bento-grid{grid-auto-columns:332px;}
                    <?php foreach ($sliderBanners as $index => $banner): ?>
                    .hp-bento-banner-<?php echo $index; ?> .cms-banner-wrapper-root{background-image:url(<?php echo $banner['image']; ?>);}
                    <?php endforeach; ?>
                </style>
                <ul class="hp-bento-grid">
                    <?php foreach ($sliderBanners as $index => $banner): ?>
                    <?php
                        $slotClass = $index === 0 ? 'hp-bento-slot-hero' : 'hp-bento-slot-tile';
                        $titleSize = $banner['title_size'] ?? '18px';
                        $gaIndex = $index + 1;
                    ?>
                    <li class="hp-bento-slot <?php echo $slotClass; ?> hp-bento-banner hp-bento-banner-<?php echo $index; ?>">
                        <div class="cms-banner-wrapper cms-text-banner-wrapper">
                            <a href="<?php echo htmlspecialchars($banner['link'], ENT_QUOTES, 'UTF-8'); ?>"
                               class="cms-banner-wrapper-root-link"
                               data-ga-promotion_id="<?php echo htmlspecialchars($banner['ga_id'], ENT_QUOTES, 'UTF-8'); ?>"
                               data-ga-promotion_name="<?php echo htmlspecialchars($banner['ga_name'], ENT_QUOTES, 'UTF-8'); ?>"
                               data-ga-creative_name="<?php echo htmlspecialchars($banner['ga_name'], ENT_QUOTES, 'UTF-8'); ?>"
                               data-ga-index="<?php echo $gaIndex; ?>"
                               data-ga-creative_slot="homepageBentoBanners"
                               title=""
                               aria-label="">
                                <div class="cms-text-banner" data-height-type="ratio" data-justify-content="flex-start" data-align-items="flex-start" data-fix-flag="1">
                                    <div class="cms-banner-scaled-wrapper">
                                        <div class="cms-banner-scaled">
                                            <div class="cms-banner-container">
                                                <div class="cms-banner-wrapper-root">
                                                    <div class="custom-flex-box" style="display:flex;flex-flow:column;place-content:center;align-items:flex-start;white-space:normal;gap:5px;max-width:300px;">
                                                        <div class="custom-flex-box-item" style="line-height:1.3;">
                                                            <span style="font-size: <?php echo $titleSize; ?>; font-weight: 700; font-style: normal; color: rgb(0, 0, 0);"><?php echo $banner['title']; ?></span>
                                                        </div>
                                                        <?php foreach ($banner['lines'] as $line): ?>
                                                        <div class="custom-flex-box-item" style="line-height: 1.42857;">
                                                            <span style="font-size: 14px;"><?php echo $line; ?></span>
                                                        </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="hp-bento-controls">
                <button class="hp-bento-pagination hp-banner-pause-button" aria-label="Pause auto-play">
                    <span class="icon-pause"></span>
                    <span class="icon-play"></span>
                    <span class="hp-bento-counter"></span>
                </button>
            </div>
        </div>
    </div>
<script type="text/javascript">
