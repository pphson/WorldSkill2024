// Text to Speech bằng ngôn ngữ Pháp (fr-FR)
document.getElementById('speakButton')?.addEventListener('click', () => {
    const addressText = document.getElementById('addressText').innerText;
    
    // Hủy lệnh nói cũ nếu có
    window.speechSynthesis.cancel();
    
    const utterance = new SpeechSynthesisUtterance(addressText);
    utterance.lang = 'fr-FR'; // Thiết lập giọng đọc chuẩn tiếng Pháp
    
    window.speechSynthesis.speak(utterance);
});