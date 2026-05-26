
<!DOCTYPE html>
<html lang="en">
<head>
<title>CSS Template</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

</head>
<body>
<button onclick="import_all()">Import All</button>
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">id</th>
      <th scope="col">Name</th>
      <th scope="col">Action</th>
      <!--<th scope="col">Description</th>-->
    </tr>
  </thead>
  <tbody>
      <?php 
$no =1;
foreach($data as $v){
   
?>
    <tr>
      <th scope="row"><?php echo $no++; ?></th>
      <td class="rows" attr_id="<?php echo $v['id'];?>"><?php echo $v['id'];?></td>
      <td><?php echo $v['post_name'];?></td>
      <td id="action_<?php echo $v['id'];?>"><a href="/import_all/<?php echo $v['id'];?>">Add product</a></td>
      <!--<td><?php echo $v['name'];?></td>-->
    </tr>
    <?php }?>
  </tbody>
</table>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


<script type="text/javascript">
var cur = 0;
var url = '<?=url('/'); ?>/import_all/';
    function import_sing(id){
        var mid = '#action_'+id;
        $(mid).html('Loading');
        $.ajax({
        url: url+id,
        type: "get",
        async: true,
        data: { },
        success: function (data) {
            if(data)
            {
                $(mid).html('Done');
                cur = find_next();
                import_sing(cur);
            }
           
        },
        error: function (xhr, exception) {
            var msg = "";
            if (xhr.status === 0) {
                msg = "Not connect.\n Verify Network." + xhr.responseText;
            } else if (xhr.status == 404) {
                msg = "Requested page not found. [404]" + xhr.responseText;
            } else if (xhr.status == 500) {
                msg = "Internal Server Error [500]." +  xhr.responseText;
            } else if (exception === "parsererror") {
                msg = "Requested JSON parse failed.";
            } else if (exception === "timeout") {
                msg = "Time out error." + xhr.responseText;
            } else if (exception === "abort") {
                msg = "Ajax request aborted.";
            } else {
                msg = "Error:" + xhr.status + " " + xhr.responseText;
            }
           
        }
    }); 
    }
    function find_next(){
        var is_find = 0;
        $('.rows').each(function(i, obj) {
    var id = parseInt($(this).attr('attr_id'));
    console.log(id);
    if(is_find == 0 && id > cur)
    {
        is_find = id;
    }
});
return is_find; 
    }
    function import_all(){
        cur = find_next();
        import_sing(cur);
    }
</script>



</body>
</html>

