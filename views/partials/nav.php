<!-- Navbar -->
<nav class="navbar navbar-custom navbar-fixed-top">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?=$this->uri('/')?>">J-Quest | お問合せ管理システム</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <?php foreach($this->menu() as $menuItem): ?>
        <li id="menu-<?=$menuItem['id']?>"><a href="<?=$menuItem['uri']?>"><?=$menuItem['name']?></a></li>
        <?php endforeach; ?>
      </ul>
      <ul class="nav navbar-nav navbar-right">        
        <!-- profile.php : Edit Profile -->
        <li id="menu-me"><a href="<?=$this->uri('/user/me')?>">アカウント: <?=$this->e($this->user()->screen_name)?></a></li>
        <li><a href="<?=$this->uri('/logout')?>"><span class="glyphicon glyphicon-off"></span>ログアウト</a></li>        
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<script src="<?=$this->asset('nav.js')?>"></script>