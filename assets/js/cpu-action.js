/**
 * Delay cpu card placement with 300ms
 */
function delayCpuCardPlacement() {
    const form = document.getElementById("cpu_play_form");

    setTimeout(() => {
        form.submit();
    }, 300);
}

export default delayCpuCardPlacement;
