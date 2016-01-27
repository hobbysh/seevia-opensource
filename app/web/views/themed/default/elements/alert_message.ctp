<?php if(debug==0){ ?>
<div class="am-modal am-modal-alert" tabindex="-1" id="my-alert">
  <div class="am-modal-dialog">
    <div class="am-modal-bd"></div>
    <div class="am-modal-footer">
      <span class="am-modal-btn"><?php echo $ld['ok']; ?></span>
    </div>
  </div>
</div>
<div class="am-modal am-modal-confirm" tabindex="-1" id="my-confirm">
  <div class="am-modal-dialog">
    <div class="am-modal-hd"><?php echo $ld['confirmed']; ?></div>
    <div class="am-modal-bd"><?php echo $ld['confirmed']; ?></div>
    <div class="am-modal-footer">
      <span class="am-modal-btn" data-am-modal-confirm><?php echo $ld['submit'] ?></span>
      <span class="am-modal-btn" data-am-modal-cancel><?php echo $ld['cancel'] ?></span>
    </div>
  </div>
</div>
<script type="text/javascript">
function alert(msg){
    try{
        $("#my-alert .am-modal-footer").show();
        $("#my-alert .am-modal-bd").html(msg);
        $("#my-alert").modal('open');
        setTimeout("$('#my-alert').modal('close');",3000);
    }catch(e){
        console.log("in alert = " + e);
    }
}

function am_alert(msg){
    try{
        $("#my-alert .am-modal-footer").hide();
        $("#my-alert .am-modal-bd").html(msg);
        $("#my-alert").modal('open');
        setTimeout("$('#my-alert').modal('close');",3000);
    }catch(e){
        console.log("in am_alert = " + e);
    }
}


</script>

<?php } ?>

