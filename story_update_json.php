<?
$family = $_GET['family'];
$family_file = file_get_contents('./data/' . $family . '.json');
$family_json = json_decode($family_file, true);
$storyId = $_GET['id'];

//The page that calls this URL will send values for responseText (the story that grandma typed out)
$family_json['stories'][$storyId]["responseText"] = $_POST['responseText'];
$output = json_encode($family_json, JSON_PRETTY_PRINT);
file_put_contents('./data/' . $family . '.json', $output);

header('Location: story.php?' . $_SERVER['QUERY_STRING']);
//header('Location: timeline.php?family=' . $family);
//die();
?>