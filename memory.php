<?
include('common.php');
$memory = $memories[$_GET['id']];
$requester = $family_json['members'][$memory['user']];
$edit_mode = $_GET['edit'];

function display_response($response) {
    global $family_json;
?>
<div class="response">
    <h5><?= $family_json['members'][$response['member']] ?> <?= isset($response['text']) ? 'writes' : 'says'?>:</h5>
    <blockquote class="bd-user-<?=$response['member']%4?>">
    <? if ($response['text']) { ?>
        <?= nl2br($response['text'])?>
    <? } else { ?>
        <video src="<?= $response['video_url']?>" controls></video>
    <? } ?>
    </blockquote>
</div>
<? } ?>

<? include 'templates/header.html' ?>
<? include 'templates/nav.html' ?>
<div id="memory" class="container-fluid">
    <div class="img-container main-photo">
        <img class="img" src="<?= $memory['photo_url']?>"/>
        <h4 class="title"><?= $memory['title'] ?></h4>
    </div>
    <div class="prompt">
        <h5><?= $requester?> asks:</h5>
        <blockquote class="bd-user-<?=$memory['user']%4?>"><?= $memory['prompt'] ?></blockquote>
    </div>
    <? if ($edit_mode) { ?>
        <h5>What's your memory of the moment?</h5>
        <div class="buttons">
            <button class="btn btn-danger btn-lg" id="video">
                <span class="glyphicon glyphicon-facetime-video"> <strong>Record</strong>
            </button>
            <button class="btn btn-default btn-lg" id="text">
                <span class="glyphicon glyphicon-comment"> <strong>Write</strong>
            </button>
        </div>
        <form id="photo-upload" action="json_upload.php" method="post" enctype="multipart/form-data" style="display:none">
            <input type="file" name="photo" accept="video/*" capture="">
            <input type="submit" name="upload" value="Upload">
        </form>
        <form action="memory_update.php?<?=$_SERVER['QUERY_STRING'] ?>" method="post" id="memory-update" style="display:none">
            <input type=hidden name="video_url" />
            <div class="form-group">
                <textarea name=text class="form-control" rows="10"></textarea>
            </div>
            <button class="btn btn-primary" type=submit>Add memory</button>
        </form>
    <? } else { ?>
        <? //for ($i = count($memory['responses']); $i >= 0; --$i) { ?>
        <? for ($i = 0; $i < count($memory['responses']); ++$i) { ?>
            <? display_response($memory['responses'][$i]) ?>
        <? } ?>
    <? } ?>
</div>
<div class="glass" style="display:none">
    <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>
</div>
<? include 'templates/footer.html' ?>
<script>
$(function() {
    $('button#video').click(function() {
        alert('Don\'t forget to turn the camera to face you!');
        $('#photo-upload input[name=photo]').click();
    });
    $('button#text').click(function() {
        $('.buttons').hide();
        $('#memory-update').show();
        setTimeout(function() {
            $("html, body").animate({ scrollTop: $(document).height() }, "easeInOutQuint");
        }, 0);
    });
    $('form#photo-upload input[name=photo]').change(function() {
        $('form#photo-upload').submit();
        $('.glass').show();
        // XXX show some spinner (or just do a non-ajax submit
    });
    $('form#photo-upload').submit(function(event) {
        event.stopPropagation(); // Stop stuff happening
        event.preventDefault(); // Totally stop stuff happening
        var data = new FormData();
        data.append('photo', $('input[type=file]')[0].files[0]);
        $.ajax({
            url: 'json_upload.php?<?= $get ?>',
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            success: function(data, textStatus, jqXHR) {
                if (data.url) {
                    // Success
                    $('form#memory-update input[name=video_url]').val(data.url);
                    $('form#memory-update').submit();
                } else {
                    // Handle errors here
                    console.log('ERRORS: ' + data.error);
                }
                $('.glass').hide();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle errors here
                console.log('ERRORS: ' + textStatus);
                $('.glass').hide();
            },
            complete: function() {
                // STOP LOADING SPINNER
                $('.glass').hide();
            }
        });
        return false;
    });
});
</script>
