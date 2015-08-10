<?php
/**
 * Created by PhpStorm.
 * User: idz
 * Date: 4/4/15
 * Time: 1:46 AM
 */

if (isset($_COOKIE["athToken"])) {
    $encrypted_user_id = $_COOKIE["athToken"];
} else {
    $encrypted_user_id = "";
}

?>

<html lang="en" ng-app="LoopyThisApp">
<head>
    <meta name="viewport" content="initial-scale=1"/>
</head>
<body layout="column">
<?php include_once("analytics_tracking.php") ?>
<md-toolbar>
    <div class="md-toolbar-tools">
        <div ng-controller="LeftToolbarController as leftNavCtrl">

            <?php if (!isset($_COOKIE["PHPSESSID"])) { ?>

                <md-button ng-click="leftNavCtrl.toggleSidenav('left')" class="md-icon-button">
                    <span show-sm hide><i class="fa fa-bars"></i></span>
                </md-button>

                <md-button ng-click="leftNavCtrl.showRegisterFeature($event)" class="md-icon-button">
                    <span hide show-gt-sm style="font-family: 'Roboto', sans-serif; font-weight: 300;"><i class="fa fa-bars"></i> Playlists</span>
                </md-button>

            <?php } else { ?>

                <md-button ng-click="leftNavCtrl.toggleSidenav('left')" class="md-icon-button">
                    <span show-sm hide><i class="fa fa-bars"></i></span>
                    <span hide show-gt-sm style="font-family: 'Roboto', sans-serif; font-weight: 300;"><i class="fa fa-bars"></i> Playlists</span>
                </md-button>

            <?php } ?>

        </div>

        <h1 hide show-gt-sm layout-align-gt-sm="left" style="font-family: 'Roboto', sans-serif;">&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;LoopyThis</h1>
        <span flex></span>

        <div class="search-container" ng-controller="SearchToolbarController as searchNavCtrl">

            <md-input-container md-no-float class="searchEngineInput search-input">
                <label>Search on youtube</label>
                <input type="text"  ng-model="searchNavCtrl.searchQuery" ng-enter="searchNavCtrl.apiSearch(searchNavCtrl.searchQuery)">
            </md-input-container>

            <md-button class="search-button" hide show-gt-sm ng-click="searchNavCtrl.apiSearch(searchNavCtrl.searchQuery)">
                <i class="fa fa-search"></i>
            </md-button>

        </div>

        <div ng-controller="RightToolbarController as rightNavCtrl">

            <?php if (!isset($_COOKIE["PHPSESSID"])) { ?>
                <md-button hide show-gt-sm ng-click="rightNavCtrl.showLogin($event)" id="navbarLoginButton">
                    Login
                </md-button>
                <md-button hide show-gt-sm ng-click="rightNavCtrl.showRegister($event)">
                    Register
                </md-button>
            <?php } else { ?>
                <md-button hide show-gt-sm ng-click="rightNavCtrl.showLogout($event)" id="navbarLogoutButton">
                    Logout
                </md-button>
            <?php } ?>

        </div>
    </div>
</md-toolbar>

<div ng-controller="SidenavController as SideCtrl" <?php if (!isset($_COOKIE["PHPSESSID"])) { ?> show-sm hide <?php } ?>>

    <md-sidenav layout="column" class="md-sidenav-left md-whiteframe-z2" md-component-id="left">

        <md-toolbar>
            <div class="md-toolbar-tools">
                <h1 layout-align-gt-sm="left">Playlists</h1>
                <span flex></span>
                <md-button ng-click="SideCtrl.toggleSidenav('left')">
                    <i class="fa fa-times"></i>
                </md-button>
            </div>
        </md-toolbar>

        <div class="Grid Grid--gutters md-Grid--2col">
            <div class="Grid-cell u-marginBl">
                <v-accordion class="vAccordion--default" multiple control="accordionB">

                    <v-pane expanded="$first">

                        <v-pane-header>
                            <h4>New Playlist</h4>
                        </v-pane-header>

                        <v-pane-content>
                            <md-input-container flex>
                                <label>Playlist name</label>
                                <input ng-model="SideCtrl.newPlaylistName" ng-enter="SideCtrl.addPlaylist(SideCtrl.newPlaylistName)">
                            </md-input-container>

                            <md-button style="width: 49%;" class="md-raised md-primary" ng-click="SideCtrl.addPlaylist(SideCtrl.newPlaylistName)">
                                Add
                            </md-button>

                            <md-button style="width: 49%;" class="md-raised md-primary" ng-click="SideCtrl.resetPlaylistName()">
                                Reset
                            </md-button>
                        </v-pane-content>

                    </v-pane>

                    <div ng-show="playlists_loading">

                        <div layout="row" layout-align="center center">
                            <div id="wrap">
                                <div class="loading outer">
                                    <div class="loading inner"></div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div ng-hide="playlists_loading">
                        <v-pane ng-repeat="playlist in playlists_glb_object">

                            <v-pane-header>
                                <h4 style="font-family: 'Roboto', sans-serif; font-weight: 400; font-size: 16px;">
                                    <i class="fa fa-play-circle-o"></i> {{ playlist.lt_playlists_name }}
                                </h4>
                            </v-pane-header>

                            <v-pane-content>

                                <div ng-repeat="song in playlist.songs">
                                    <md-button ng-click="SideCtrl.playVideo(song.ltsp_song_id)"  style="font-family: 'Roboto', sans-serif; font-weight: 300; font-size: 14px;">
                                        {{song.ltsp_song_name | limitTo: 32}}
                                    </md-button>

                                    <md-button ng-click="SideCtrl.deleteSongFromPlaylist(playlist.lt_playlists_id, song.ltsp_song_id, $parent.$index, $index)" style="float: right">
                                        <i class="fa fa-times ng-scope"></i>
                                    </md-button>
                                </div>

                            </v-pane-content>
                        </v-pane>
                    </div>
                </v-accordion>
            </div>
        </div>
        <?php
        if (!isset($_COOKIE["PHPSESSID"])) {
            ?>

            <md-button show-sm hide ng-click="SideCtrl.triggerShowLogin($event)">
                Login
            </md-button>

            <md-button show-sm hide ng-click="SideCtrl.triggerShowRegister($event)">
                Register
            </md-button>

        <?php
        } else {
            ?>

            <md-button show-sm hide ng-click="SideCtrl.triggerShowLogout($event)">
                Logout
            </md-button>

        <?php
        }
        ?>

    </md-sidenav>
</div>

<div style="height: auto; overflow: auto;">

    <div layout="row" layout-align="center start">

        <md-whiteframe class="md-whiteframe-z1 custom-movie-container results-list-container" layout-align="center center" ng-controller="SearchResultsController as searchResCtrl" ng-show="video_results_exist">

            <md-list class="listOfSongs">
                <md-list-item class="md-3-line" ng-repeat="result_object in resulting_objects" ng-click="searchResCtrl.insertVideo(result_object.items[0].id)" style="margin-top: 10px; margin-bottom: 10px;">
                    <img ng-src="{{result_object.items[0].snippet.thumbnails.default.url}}" class="md-desc-image" style="margin-left: 10px;" alt="{{ result_object.items[0].snippet.title | limitTo: 20}}"/>

                    <div class="title-plus-desc-container" style="float: left; width: 75%; padding-right: 15px; padding-left: 15px; height: 60px;">

                        <div class="md-list-item-text">
                            <h4 class="result_object_title main-video-title" style="font-family: 'Roboto', sans-serif; font-weight: 400;">{{ result_object.items[0].snippet.title |limitTo: 45}}</h4>
                        </div>

                        <div class="md-list-item-text">
                            <h5 class="result_object_title main-video-description" style="font-family: 'Roboto', sans-serif; font-weight: 300;"> {{result_object.items[0].snippet.description | limitTo: 165}}</h5>
                        </div>

                    </div>

                    <div class="buttons-container" style="height: 60px;">

                        <md-button class="md-fab md-mini" aria-label="Eat cake" style="text-align: center;" hide>
                            <i class="fa fa-plus-square"></i>
                            <md-tooltip md-direction="right">
                                Add to playlist
                            </md-tooltip>
                        </md-button>

                        <md-button class="md-fab md-mini" aria-label="Eat cake" style="text-align: center;">
                            <i class="fa fa-info"></i>
                            <md-tooltip md-direction="right">
                                Views: {{ result_object.items[0].statistics.viewCount}} <br>
                                Likes: {{ result_object.items[0].statistics.likeCount}}  <br>
                                Dislikes: {{ result_object.items[0].statistics.dislikeCount}}
                            </md-tooltip>
                        </md-button>

                    </div>

                </md-list-item>
            </md-list>
        </md-whiteframe>
    </div>

    <div layout="row" layout-align="center start">
        <md-whiteframe class="md-whiteframe-z1 custom-movie-container" layout layout-align="center center">
            <div id='player'></div>
        </md-whiteframe>
    </div>

    <div layout="row" layout-align="space-between center" class="custom-actions-container" ng-controller="GeneralActionsController as genActCtrl">

        <?php if (!isset($_COOKIE["PHPSESSID"])) { ?>

            <md-button class="md-fab md-mini" aria-label="Eat cake" ng-click="genActCtrl.showRegisterFeature($event)">
                <i class="fa fa-plus-square"></i>
                <md-tooltip md-direction="right">
                    Add to playlist
                </md-tooltip>
            </md-button>

        <?php } else { ?>

            <md-button class="md-fab md-mini" aria-label="Eat cake" ng-click="genActCtrl.showPlaylistOptions($event)">
                <i class="fa fa-plus-square"></i>
                <md-tooltip md-direction="right">
                    Add to playlist
                </md-tooltip>
            </md-button>

        <?php } ?>

        <md-button class="md-fab md-mini" aria-label="Eat cake" ng-class="genActCtrl.repeatActionsClasses()" ng-click="genActCtrl.repeatActions()">
            <i ng-class="genActCtrl.repeatActionsIconsClasses()"></i>
            <md-tooltip md-direction="right">
                {{genActCtrl.repeatActionMsg}}
            </md-tooltip>
        </md-button>

        <md-button class="md-fab md-mini" aria-label="Eat cake">
            <i class="fa fa-random"></i>
            <md-tooltip md-direction="right">
                Shuffle
            </md-tooltip>
        </md-button>

    </div>

    <div ng-controller="InitStreamsController">

        <md-button ng-init="trk()" hide>
            d
        </md-button>

        <md-button ng-click="test()" hide>
            dd
        </md-button>

    </div>

    <div layout="row" layout-align="center start">

        <md-whiteframe class="md-whiteframe-z1 custom-movie-container results-list-container" layout-align="center center" ng-controller="SearchResultsController as searchResCtrl">
            <h1>Now with more awesome features</h1>
        </md-whiteframe>

    </div>

    <div class="footer">
        <div class="container">
            <div class="footer-wp">

                <ul class="footer-buttons">
                    <li class="footer-button">
                        <a class="ft-link" href=""> Contact</a>
                    </li>

                    <li class="footer-button">
                        <a class="ft-link" href="manifesto.php">Manifesto</a>
                    </li>

                    <li class="footer-button">
                        <a class="ft-link" href=""> Blog</a>
                    </li>

                    <li class="footer-button">
                        <a class="ft-link" href="terms.php">Terms</a>
                    </li>

                    <li class="footer-button">
                        <a class="ft-link" href="privacy.php"> Privacy Policy</a>
                    </li>
                </ul>

                <div class="footer-info pull-right">
                    Copyright (c) 2008 - Loopy This
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/angular_material/0.8.3/angular-material.min.css">
<link href='http://fonts.googleapis.com/css?family=Roboto:400,100,300' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="public/css/main2.css">
<link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">

<!--<div id='player'></div>-->
<!-- Angular Material Dependencies -->
<script src="assets/angular/js/angular.min.js"></script>
<script src="assets/angular/js/angular-animate.min.js"></script>
<script src="assets/angular/js/angular-aria.min.js"></script>
<script src="assets/angular/js/angular-material.min.js"></script>
<script src="assets/angular/js/v-accordion.js"></script>
<script src="http://www.youtube.com/player_api"></script>
<script src="public/js/main2.js"></script>

</body>
</html>