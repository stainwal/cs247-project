<?
$family = $_GET['family'];
$family_file = file_get_contents('./data/' . $family . '.json');
$family_json = json_decode($family_file, true);
$id = $_GET['id'];
$story = $family_json['stories'][$id];
//echo var_dump($story);

$get = 'family=' . $_GET['family'] . '&id=' . $_GET['id'];

// templates
function new_comment($commentText, $family) { ?>
  <div class="row comment">
      <img class="col-xs-2" src="./data/<?= $family ?>/parent.jpg" />
      <div class="col-xs-10">
          <textarea name="responseText" style="height: 6em"><?= $commentText ?></textarea>
      </div>
  </div>
<? }

?>

<? include 'templates/header.html' ?>
<? include 'templates/nav.html' ?>
<div id="request" class="container-fluid">
    <h2>Story from <?= $story['date'] ?></h2>
    <div class="row main-photo-row">
        <div class="col-xs-12">
            <div class="main-photo" style="background-image:url(<?= $_POST['photo_url']?>);"></div>
        </div>
    </div>
    <div class="main-photo">
        <div id="step-0" class="row">
            <div class="col-xs-12 upload-photo">
              <? if ($story['imagePath']) { ?>
              <img src="<?= $story['imagePath'] ?>" class="img img-responsive"/>
              <? } ?>
            </div>
        </div>
        <form action="save_response.php?<?= $get ?>" method="post">
        <input name="date" type="hidden" placeholder="When was this photo taken?" />
        <div class="row">&nbsp;</div>
        <div class="row comment">
            <img class="col-xs-2" src="./data/<?= $family ?>/user.jpg" />
            <div class="col-xs-10">
                <p><?= $story["prompt"] ?></p>
            </div>
        </div>
        <? new_comment("Type the story here", $family)?>
        <div id="step-3" class="row">
            <div class="col-xs-10"></div>
            <div class="col-xs-2">
                <button type="submit" class="btn btn-primary" id="send">Send</button>
            </div>
        </div>
    </div>
</div>
<? include 'templates/footer.html' ?>
