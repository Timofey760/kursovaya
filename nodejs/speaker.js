
const synth = window.speechSynthesis;
let voices = [];

function populateVoiceList() {
  voices = synth.getVoices();
  voices = voices.filter(a => a.lang.includes("ru") && a.name.includes("Google"));
  //if not exists Google voices
   if (voices.length == 0) {
     voices = voices.filter(a => a.lang.includes("ru"));
  }
  console.log(voices);
}

//voices=synth.getVoices();



setTimeout(() => {

  //populateVoiceList();
}, 1500);

if (synth.onvoiceschanged !== undefined) {
  synth.onvoiceschanged = populateVoiceList;
}

function log(message) {
  console.log(message);
}

function speak(text, delay = 0, callback = log) {
  if (synth.speaking) {
    console.error("speechSynthesis.speaking");
    return;
  }
  
  synth.cancel();

  setTimeout(() => {
    const utterThis = new SpeechSynthesisUtterance(text);

    utterThis.onend = function (event) {
      //console.log('speak end');
      callback('speak end');
    };

    utterThis.onerror = function (event) {
      console.error("SpeechSynthesisUtterance.onerror");
    };
    utterThis.voice = voices[0]
    //alert(voices)
    utterThis.pitch = 1;
    utterThis.rate = 1.02;
    utterThis.lang = "ru-RU";
    
    synth.speak(utterThis);
  }, delay);

}



