
// tee uusi haku infoboksista klikatun hakusanan mukaan
function uusiHaku(haettava) {
    let siivottuArvo = haettava
        .replace(" & ", "&")
        .replace(" + ", "+");
    document.getElementById("close").click();
    document.getElementById("hakukentta").value = siivottuArvo;
    document.getElementById("hakubutton").click();
    siirraYlos();
}

// artistihakutulosten järjestäminen
function naytaArtisti(artisti, naytaTiedot) {
    let laatikko = document.createElement("div");
    laatikko.innerHTML = `
        <div class='tuloslaatikko'>
        <a>
            <img src="black-1296338_640.png" alt="artistin default-kuva" width="150" height="150"></img>
            <h3>${artisti.name}</h3>
        </a>
        <button id="katsoLisaa">Katso lisää</button>
        </div>
        `;
    laatikko.onclick=()=> naytaTiedot(artisti.name)
    document.getElementById('tulokset').appendChild(laatikko)
}


// albumihakutulosten järjestäminen
function naytaAlbumi(albumi, naytaTiedot) {
    let laatikko = document.createElement("div");
    laatikko.innerHTML = `
        <div class='tuloslaatikko'>
        <a>
            <img src="${albumi.image[2]["#text"]}" width="160" height="160"></img>
            <h3>${albumi.artist.name}:<br></h3>
            <p class = "hakutulos">${albumi.name}</p>
        </a>
        <button id="katsoLisaa">Katso lisää</button>
        </div>
        `;
    laatikko.onclick=()=> naytaTiedot(albumi.name, albumi.artist.name)
    document.getElementById('tulokset').appendChild(laatikko)
}

// takaisin ylös -napin näyttäminen
function scrollFunction(takaisinYlos) {
    if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
      takaisinYlos.style.display = "block";
    } else {
      takaisinYlos.style.display = "none";
    }
  }
  
  // siirtyminen ylös
function siirraYlos() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}
  