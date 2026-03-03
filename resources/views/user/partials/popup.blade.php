<div class="modal fade " id="commonModal" tabindex="-1">
  <div class="modal-dialog modal-lg custom-modal">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="commonheader" style="font-weight: bold;color:black ">Details</h5>
       <button type="button" class="btn-close" id="forceCloseBtn">X</button>
      </div>
      <div class="modal-body" id="commonModalBody">
      </div>
    </div>
  </div>
</div>
<style>
.custom-modal {
    max-width: 70%;
}

/* #commonModal {
    height: 90vh;   
} */

</style>
<script>
document.addEventListener("click", function (e) {

    if (e.target && e.target.id === "forceCloseBtn") {

        var modalEl = document.getElementById('commonModal');

        // Remove bootstrap classes manually
        modalEl.classList.remove("show");
        modalEl.style.display = "none";

        // Remove backdrop
        var backdrop = document.querySelector(".modal-backdrop");
        if (backdrop) {
            backdrop.remove();
        }

        // Restore body scroll
        document.body.classList.remove("modal-open");
        document.body.style.overflow = "";
        document.body.style.paddingRight = "";
    }

});

</script>
