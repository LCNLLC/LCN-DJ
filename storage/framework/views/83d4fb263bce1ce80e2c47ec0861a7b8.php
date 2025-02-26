<!doctype html>
<?php if(\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1): ?>
<html dir="rtl" lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<?php else: ?>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<?php endif; ?>
<head>
	<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
	<meta name="app-url" content="<?php echo e(getBaseURL()); ?>">
	<meta name="file-base-url" content="<?php echo e(getFileBaseURL()); ?>">

	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Favicon -->
	<link rel="icon" href="<?php echo e(uploaded_asset(get_setting('site_icon'))); ?>">
	<title><?php echo e(get_setting('website_name').' | '.get_setting('site_motto')); ?></title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>

	<!-- google font -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">

	<!-- aiz core css -->
	<link rel="stylesheet" href="<?php echo e(static_asset('assets/css/vendors.css')); ?>">
    <?php if(\App\Models\Language::where('code', Session::get('locale', Config::get('app.locale')))->first()->rtl == 1): ?>
    <link rel="stylesheet" href="<?php echo e(static_asset('assets/css/bootstrap-rtl.min.css')); ?>">
    <?php endif; ?>
	<link rel="stylesheet" href="<?php echo e(static_asset('assets/css/aiz-core.css')); ?>">

    <style>
        body {
            font-size: 12px;
        }
    </style>
	<script>
    	var AIZ = AIZ || {};
        AIZ.local = {
            nothing_selected: '<?php echo translate('Nothing selected', null, true); ?>',
            nothing_found: '<?php echo translate('Nothing found', null, true); ?>',
            choose_file: '<?php echo e(translate('Choose file')); ?>',
            file_selected: '<?php echo e(translate('File selected')); ?>',
            files_selected: '<?php echo e(translate('Files selected')); ?>',
            add_more_files: '<?php echo e(translate('Add more files')); ?>',
            adding_more_files: '<?php echo e(translate('Adding more files')); ?>',
            drop_files_here_paste_or: '<?php echo e(translate('Drop files here, paste or')); ?>',
            browse: '<?php echo e(translate('Browse')); ?>',
            upload_complete: '<?php echo e(translate('Upload complete')); ?>',
            upload_paused: '<?php echo e(translate('Upload paused')); ?>',
            resume_upload: '<?php echo e(translate('Resume upload')); ?>',
            pause_upload: '<?php echo e(translate('Pause upload')); ?>',
            retry_upload: '<?php echo e(translate('Retry upload')); ?>',
            cancel_upload: '<?php echo e(translate('Cancel upload')); ?>',
            uploading: '<?php echo e(translate('Uploading')); ?>',
            processing: '<?php echo e(translate('Processing')); ?>',
            complete: '<?php echo e(translate('Complete')); ?>',
            file: '<?php echo e(translate('File')); ?>',
            files: '<?php echo e(translate('Files')); ?>',
        }
	</script>
    <style>
        #edit_map{
            width: 100%;
            height: 250px;
            }g

            .pac-container { z-index: 100000; }

            .js-cookie-consent.cookie-consent {
                width: 30%;
                position: fixed;
                z-index: 99999;
                text-align: center;
                color: white;
                background: black;
                left: 25%;
                padding: 20px;
                left: 83%;
                transform: translate(-50%, 0);
                margin-bottom: 22px;
            }
            .cookie-consent .max-w-7xl.mx-auto.px-6 {
                padding: 0;
            }
            .js-cookie-consent-agree {
                cursor: pointer;
                border-radius: 10px;
            }
            @media (max-width: 600px){
               .js-cookie-consent.cookie-consent {
                width: 100%;
                left: 50%;
            }
        }
    </style>

</head>
<body class="">

	<div class="aiz-main-wrapper">
        <?php echo $__env->make('backend.inc.admin_sidenav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
		<div class="aiz-content-wrapper">
            <?php echo $__env->make('backend.inc.admin_nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
			<div class="aiz-main-content">
				<div class="px-15px px-lg-25px">
                    <?php echo $__env->yieldContent('content'); ?>
				</div>
				<div class="bg-white text-center py-3 px-15px px-lg-25px mt-auto">
					<p class="mb-0">&copy; <?php echo e(get_setting('site_name')); ?> v<?php echo e(get_setting('current_version')); ?></p>
				</div>
			</div><!-- .aiz-main-content -->
		</div><!-- .aiz-content-wrapper -->
	</div><!-- .aiz-main-wrapper -->

    <?php echo $__env->yieldContent('modal'); ?>


	<script src="<?php echo e(static_asset('assets/js/vendors.js')); ?>" ></script>
	<script src="<?php echo e(static_asset('assets/js/aiz-core.js')); ?>" ></script>

    <?php echo $__env->yieldContent('script'); ?>

    <script type="text/javascript">
	    <?php $__currentLoopData = session('flash_notification', collect())->toArray(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	        AIZ.plugins.notify('<?php echo e($message['level']); ?>', '<?php echo e($message['message']); ?>');
	    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


        if ($('#lang-change').length > 0) {
            $('#lang-change .dropdown-menu a').each(function() {
                $(this).on('click', function(e){
                    e.preventDefault();
                    var $this = $(this);
                    var locale = $this.data('flag');
                    $.post('<?php echo e(route('language.change')); ?>',{_token:'<?php echo e(csrf_token()); ?>', locale:locale}, function(data){
                        location.reload();
                    });

                });
            });
        }
        function menuSearch() {
            const filter = $("#menu-search").val().toUpperCase();
            // Filter out menu items based on text and valid href
            const items = $("#main-menu").find("a").filter(function() {
                const textElement = $(this).find(".aiz-side-nav-text");
                if (textElement.length > 0) {
                    const text = textElement.text().toUpperCase();
                    return text.indexOf(filter) > -1 && $(this).attr('href') !== '#';
                }
                return false;
            });

            console.log("Filter:", filter);
            console.log("Filtered items:", items);

            if (filter !== '') {
                $("#main-menu").addClass('d-none');
                $("#search-menu").html('');

                if (items.length > 0) {
                    items.each(function() {
                        const text = $(this).find(".aiz-side-nav-text").text();
                        const link = $(this).attr('href');
                        $("#search-menu").append(
                            `<li class="aiz-side-nav-item">
                                <a href="${link}" class="aiz-side-nav-link">
                                    <i class="las la-ellipsis-h aiz-side-nav-icon"></i>
                                    <span>${text}</span>
                                </a>
                            </li>`
                        );
                    });
                } else {
                    $("#search-menu").html(
                        `<li class="aiz-side-nav-item">
                            <span class="text-center text-muted d-block"><?php echo e(translate('Nothing Found')); ?></span>
                        </li>`
                    );
                }
            } else {
                $("#main-menu").removeClass('d-none');
                $("#search-menu").html('');
            }
        }

    </script>

    <script>
        $("#plan_type").change(function() {
        var selectedValue = $(this).val();

        if (selectedValue =='days') {
          $("#days").show();
          $("#click").hide();
          $("#per_click_price").hide();
          $("#price_lab_2").show();

        } else {
          $("#days").hide();
          $("#click").show();
          $("#per_click_price").show();
          $("#price_lab_2").hide();
        }

      });
    </script>

</body>
</html>
<?php /**PATH /var/www/html/serverFiles/resources/views/backend/layouts/app.blade.php ENDPATH**/ ?>