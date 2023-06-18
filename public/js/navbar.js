const herder = document.querySelector("header");
const bottom = document.querySelector("footer");

function setHeader() {
    herder.innerHTML += /*html*/ `
    <div class="Title">
        <div class="Title-Introduce">
            <a href="./index.html">
                <img class="Title-Icon" src="./images/Logo.png">
            </a>
                <ul class="Title-Menu">
                    <li>
                        <a href="">1</a>
                    </li>
                    <li>
                        <a href="">2</a>
                    </li>
                    <li>
                        <a href="">3</a>
                    </li>
                    <li>
                        <a href=""></a>
                    </li>
                </ul>
        </div>
        <div class="Title-Login">
            <div class="Title-Login-Message"></div>
            <div class="Title-Login-Icon">
                <img src="https://gamelet.online/clients/assets/v1/img/none_login_photo.png">
            </div>
        </div>
    </div>
    `
}
function setFooter() {
    bottom.innerHTML += /*html*/ `
    <div class="copyright-container">
        © 2023 CHANGE
    </div> 
    `
}
