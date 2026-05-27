<?php if(isset($_SESSION['mensaje'])): ?>

<div id="toast" class="toast">
    <?php echo $_SESSION['mensaje']; ?>
</div>

<script>
setTimeout(function(){
    let toast = document.getElementById("toast");
    if(toast){
        toast.classList.add("hide");
    }
}, 2000);

setTimeout(function(){
    let toast = document.getElementById("toast");
    if(toast){
        toast.style.display = "none";
    }
}, 2600);
</script>

<?php unset($_SESSION['mensaje']); ?>
<?php endif; ?>