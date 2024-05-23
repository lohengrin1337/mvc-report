/**
 * Make card-slots clickable, and send form with id from clicked slot
 */
function makeCardSlotsClickable() {
    const cardSlots = document.querySelectorAll(".empty-slot");
    const form = document.getElementById("place_card_form");
    const formInput = document.getElementById("card_slot_input");

    cardSlots.forEach(slot => {
        slot.addEventListener("click", (event) => {
            // update value of input and submit form
            formInput.value = event.target.dataset.slotId;
            form.submit();
        });
    });
}

document.addEventListener("DOMContentLoaded", () => {
    if (document.getElementById("place_card_form")) {

    }
});

export default makeCardSlotsClickable;
