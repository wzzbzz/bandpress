<?php

namespace app\Views\ComponentViews;

// HTML HEADER TEMPLATE
// from doc to body
class Header{
    
    protected $data;

    // page title
    protected $pageTitle = 'Default Page Title';

    // open graph info
    protected $ogTitle = 'Default OG Title';
    protected $ogDescription = 'Default OG Description';
    protected $ogImage = '';
    protected $ogUrl = '';

    // twitter stuff
    protected $twitterCardImage = '';	
	protected $twitterHandle = 'wzzbzz';
	
    // TBD will contain an SEO view object to render 
    // schema, meta tages, etc.
    protected $SEO;

    // body CSS classes
    protected $css;

    public function __construct($data=null){
        $this->data = $data;
	}
    
    public function __destruct(){}


    // setters and getters
    public function setPageTitle($title){
        $this->pageTitle = $title;
    }

    public function getPageTitle(){
        return $this->pageTitle;
    }

    public function renderPageTitle(){
        return htmlentities($this->pageTitle);
    }
    
    public function setOgTitle($title){
        $this->ogTitle = $title;
    }
    
    public function getOgTitle(){
        return $this->ogTitle;
    }

    public function renderOgTitle(){
        return htmlentities($this->ogTitle);
    }
    
    public function setOgDescription($desc){
        $this->ogDescription = $desc;
    }

    public function getOgDescription(){
        return $this->ogDescription;
    }

    public function renderOgDescription(){
        return htmlentities($this->ogDescription);
    }
    
    public function setOgImage($image){
        $this->ogImage = $image;
    }
	
    public function getOgImage(){
        return $this->ogImage;
    }

    public function renderOgImage(){
        return $this->ogImage;
    }

	public function setOgUrl( $url ){
		$this->ogUrl = $url;
	}

    public function getOgUrl( $url ){
        return $this->ogUrl;
    }
    
    public function renderOgUrl(){
        return $this->ogUrl;
    }
	
	public function setTwitterCardImage( $image ){
		$this->twitter_card_image = $image;
	}

    public function getTwitterCardImage(){
        return $this->twitter_card_image;
    }

    public function renderTwitterCardImage(){
        return $this->twitterCardImage;
    }
    
	public function setTwitterHandle( $handle ){
		$this->twitterHandle = $handle;
	}
	
    public function getTwitterHandle( $handle ){
		return $this->twitterHandle;
	}

    public function renderTwitterHandle( ){
        return $this->twitterHandle;
    }
    
	public function setCss($css){
		$this->css = $classes;
	}
	
    public function getCss(){
		return $this->css;
	}

    public function renderCss(){
        return $this->css;
    }
	
	public function render(){
		
        ?><!doctype html>
<html>
	<head>

        <title><?= $this->renderPageTitle();?></title>
        <meta name="description" content="<?= $this->renderOgDescription()?>">
        
        <!--  Open Graph Meta Tags -->
        
        <meta property="og:title" content="<?= $this->renderOgTitle();?>" />
        <meta property="og:description" content="<?=$this->renderOgDescription(); ?>" />
	
        <meta property="og:image" content = "<?php echo $this->renderOgImage();?>" />
        <meta property="og:url" content="<?php echo $this->renderOgUrl();?>">
	
        <!--  Twitter Card Meta Tags -->
        
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:site" content="@wzzbzz">
        <meta name="twitter:creator" content="<?php echo $this->renderTwitterHandle();?>">
        <meta name="twitter:title" content="<?php echo htmlentities($this->og_title);?>">
        <meta name="twitter:description" content="<?= $this->og_description ; ?>">
        <meta name="twitter:image" content="<?php echo $this->twitter_card_image;?>">
        
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo bloginfo("template_url");?>/assets/img/favicon.png" />
        <script src="https://kit.fontawesome.com/26e170e2d9.js" crossorigin="anonymous"></script>

         <?php wp_head(); ?>
		<meta name="viewport" content="width=device-width, initial-scale=1">

	</head>

	<body class="">
		<!-- Initialize FB SDK -->
		<!--<div id="fb-root"></div>
        <script>
            (function(d, s, id) {
		        var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=723771347696137&version=v2.0";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>-->

    <?php
    }

}