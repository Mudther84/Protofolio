document.querySelector('.contact-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const message = document.getElementById('message').value;

    const submitBtn = document.querySelector('.submit-btn');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Sending...';
    submitBtn.disabled = true;

    try {
      const response = await fetch('api/send.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json; charset=utf-8',
        },
        body: JSON.stringify({ name, email, message }),
      });

      const result = await response.json();

      if (result.success) {
        alert('✅ تم إرسال رسالتك بنجاح!');
        document.querySelector('.contact-form').reset();
      } else {
        alert('❌ خطأ: ' + result.message);
      }
    } catch (error) {
      alert('❌ حدث خطأ في الاتصال. تأكد من أن الخادم يدعم PHP.');
      console.error('Error:', error);
    } finally {
      submitBtn.textContent = originalText;
      submitBtn.disabled = false;
    }
  });