function generateShortLink() {
    var originalUrl = document.getElementById("originalUrl").value;
    var customDomain = document.getElementById("customDomain").value;
    var shortLinkResult = document.getElementById("shortLinkResult");

    // You can customize the logic for generating the short link here
    var shortLink = generateShortLinkLogic(originalUrl, customDomain);

    shortLinkResult.innerHTML =
        'Shortened URL: <a href="' +
        shortLink +
        '" target="_blank">' +
        shortLink +
        "</a>";
}

function generateShortLinkLogic(originalUrl, customDomain) {
    // Implement your logic to generate the short link here
    // For simplicity, let's concatenate the custom domain and a random string
    var randomString = Math.random().toString(36).substring(7);
    return "http://" + customDomain + "/" + randomString;
}
