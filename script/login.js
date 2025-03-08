let mail = document.querySelector('input[type="email"]');
let mailSave;
let saveValue;

if (history.state) {
  saveValue = history.state.mail;
  mailSave = saveValue;
  mail.value = mailSave;
}

mail.addEventListener("input", function () {
  let value = mail.value;
  if (value !== mailSave) {
    mailSave = value;
    history.replaceState({ mail: value }, "");
    localStorage.setItem("mail", value);
  }
});
