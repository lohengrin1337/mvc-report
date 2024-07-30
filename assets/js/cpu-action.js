/**
 * Delay cpu card placement
 */
function delayCpuCardPlacement() {
    const form = document.getElementById("cpu_play_form");

    console.log("inside delayCpuCardPlacement");

    setTimeout(() => {
        form.submit();
    }, 500);
}

export default delayCpuCardPlacement;
