<?php
/** @var yii\web\View $this */

/** @var app\models\Sites $websiteModel */

use yii\helpers\HtmlPurifier;

$this->title = HtmlPurifier::process("Сайт $websiteModel->url работает сегодня?");

$this->registerMetaTag([
    'name' => 'description',
    'content' => HtmlPurifier::process("Узнайте, доступен ли сегодня сайт $websiteModel->url в России?")
]);


?>


<div class="container p-5 sm:p-10">
    <!--    <h1 class="w-full my-5 text-5xl font-bold leading-tight text-center text-gray-800">-->
    <?php //echo $websiteModel->url; ?><!--</h1>-->

    <div class="p-5 bg-white flex items-center mx-auto border-b  mb-10 border-gray-200 rounded-lg sm:flex-row flex-col">
        <div class="sm:w-32 sm:h-32 h-20 w-20 sm:mr-10 inline-flex items-center justify-center flex-shrink-0">
            <img width="128" src="<?php echo $websiteModel->image_url; ?>" alt="<?php echo $websiteModel->url; ?> logo">
        </div>
        <div class="flex-grow sm:text-left text-center mt-6 sm:mt-0">
            <h1 class="text-black text-2xl title-font font-bold mb-2"><?php echo $websiteModel->url; ?></h1>
            <p class="leading-relaxed text-base"><?php echo $websiteModel->title ?></p>
            <p class="leading-relaxed text-base pb-4"><?php echo $websiteModel->description ?></p>

<!--            <div class="py-4">-->
<!--                <div class=" inline-block mr-2">-->
<!--                    <div class="flex  pr-2 h-full items-center">-->
<!--                        <svg class="text-green-500 w-6 h-6 mr-1" width="24" height="24" viewBox="0 0 24 24"-->
<!--                             stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"-->
<!--                             stroke-linejoin="round">-->
<!--                            <path stroke="none" d="M0 0h24v24H0z"></path>-->
<!--                            <circle cx="12" cy="12" r="9"></circle>-->
<!--                            <path d="M9 12l2 2l4 -4"></path>-->
<!--                        </svg>-->
<!--                        <p class="title-font font-medium">Russia</p>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="inline-block mr-2">-->
<!--                    <div class="flex  pr-2 h-full items-center">-->
<!--                        <svg class="text-green-500 w-6 h-6 mr-1" width="24" height="24" viewBox="0 0 24 24"-->
<!--                             stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"-->
<!--                             stroke-linejoin="round">-->
<!--                            <path stroke="none" d="M0 0h24v24H0z"></path>-->
<!--                            <circle cx="12" cy="12" r="9"></circle>-->
<!--                            <path d="M9 12l2 2l4 -4"></path>-->
<!--                        </svg>-->
<!--                        <p class="title-font font-medium">Netherlands</p>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class=" inline-block mr-2">-->
<!--                    <div class="flex  pr-2 h-full items-center">-->
<!--                        <svg class="text-green-500 w-6 h-6 mr-1" width="24" height="24" viewBox="0 0 24 24"-->
<!--                             stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"-->
<!--                             stroke-linejoin="round">-->
<!--                            <path stroke="none" d="M0 0h24v24H0z"></path>-->
<!--                            <circle cx="12" cy="12" r="9"></circle>-->
<!--                            <path d="M9 12l2 2l4 -4"></path>-->
<!--                        </svg>-->
<!--                        <p class="title-font font-medium">USA</p>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class=" inline-block mr-2">-->
<!--                    <div class="flex  pr-2 h-full items-center">-->
<!--                        <svg class="text-gray-500 w-6 h-6 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor"-->
<!--                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">-->
<!--                            <circle cx="12" cy="12" r="10"></circle>-->
<!--                            <line x1="15" y1="9" x2="9" y2="15"></line>-->
<!--                            <line x1="9" y1="9" x2="15" y2="15"></line>-->
<!--                        </svg>-->
<!--                        <p class="title-font font-medium">Germany</p>-->
<!--                    </div>-->
<!--                </div>-->
<!---->
<!--                <div class=" inline-block mr-2">-->
<!--                    <div class="flex  pr-2 h-full items-center">-->
<!--                        <svg class="text-gray-500 w-6 h-6 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor"-->
<!--                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">-->
<!--                            <circle cx="12" cy="12" r="10"></circle>-->
<!--                            <line x1="15" y1="9" x2="9" y2="15"></line>-->
<!--                            <line x1="9" y1="9" x2="15" y2="15"></line>-->
<!--                        </svg>-->
<!--                        <p class="title-font font-medium">China</p>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class=" inline-block mr-2">-->
<!--                    <div class="flex  pr-2 h-full items-center">-->
<!--                        <svg class="text-gray-500 w-6 h-6 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor"-->
<!--                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">-->
<!--                            <circle cx="12" cy="12" r="10"></circle>-->
<!--                            <line x1="15" y1="9" x2="9" y2="15"></line>-->
<!--                            <line x1="9" y1="9" x2="15" y2="15"></line>-->
<!--                        </svg>-->
<!--                        <p class="title-font font-medium">Turkey</p>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
            <div class="md:flex font-bold text-gray-800">
                <div class="w-full md:w-1/2 flex space-x-3">
                    <div class="w-1/2">
                        <h2 class="text-gray-500">Status</h2>
                        <p><svg class="text-green-500 w-6 h-6 inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z"></path>
                                <circle cx="12" cy="12" r="9"></circle>
                                <path d="M9 12l2 2l4 -4"></path>
                            </svg>OK</p>
                    </div>
                    <div class="w-1/2">
                        <h2 class="text-gray-500">HTTP Code</h2>
                        <p><?php echo $websiteModel->last_http_code ?></p>
                    </div>
                </div>
                <div class="w-full md:w-1/2 flex space-x-3">
                    <div class="w-1/2">
                        <h2 class="text-gray-500">Time to first byte</h2>
                        <p><?php echo $websiteModel->ttfb ?> ms</p>
                    </div>
                    <div class="w-1/2">
                        <h2 class="text-gray-500">Last check:</h2>
                        <p><?php echo date('H:i:s d/m', $websiteModel->updated_at) ?></p>
                    </div>
                </div>
            </div>
            <a class="mt-3 text-indigo-500 inline-flex items-center">Learn More
                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                     class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                    <path d="M5 12h14M12 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>


    <div class="flex">
        <div class="w-full col-span-1 p-6 bg-white rounded-lg shadow sm:flex-row">
            <h1>
                <?php echo $websiteModel->url; ?>
            </h1>

            <p>Title: <?php echo $websiteModel->title ?></p>

            <p>Description: <?php echo $websiteModel->description ?></p>

            <p>Http code: <?php echo $websiteModel->last_http_code ?></p>

            <p>Time to first byte: <?php echo $websiteModel->ttfb ?></p>

            <p>Last check: <span class="datetime-str"
                                 data-content="<?php echo $websiteModel->updated_at ?>"><?php echo date('H:i:s d/m', $websiteModel->updated_at) ?></span>
            </p>
            <p></p>
        </div>

        <div class="w-full col-span-1 p-6 bg-white rounded-lg shadow sm:flex-row">

        </div>

    </div>

</div>
