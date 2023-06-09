const herder = document.querySelector("header");

function setHeader() {
    herder.innerHTML += /*html*/ `
    <div class="header-display">
            <span class="company-name">Yummy Restaurant Group Limited</span>
            <div class="user-title">
                <div class="user-name"></div>
                <div class="user-icon"></div>   
                <input class="user-logout" type="button" value="Logout">       
        </div>
    </div>
    `
}

setHeader();