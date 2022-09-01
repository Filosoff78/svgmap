/*
 * svg-export.js - Javascript SVG parser and renderer on Canvas
 * version 1.2.0
 * MIT Licensed
 * Sharon Choong (https://sharonchoong.github.io/about.html)
 * https://sharonchoong.github.io/svg-export
 *
 */
import { Canvg } from 'canvg';
(() => {
    BX.Vue.Map.Complete = () => {
        const svgContent = BX.Vue.Map.Canvas.svg();

        downloadPNG(svgContent);
        return true;
        downloadSvg(svgContent, "chart title name");
    }

    function downloadPNG(svg) {
        const imageType = "png";
        const svgName = "chart";

        var svgElement = getSvgElement(svg);
        if (!svgElement) { return; }

        //get canvas and svg element.
        var canvas = document.createElement("canvas");

        var svgString = setupSvg(svgElement, svg);

        var ctx = canvas.getContext("2d");
        var v = Canvg.fromString(ctx, svgString, { anonymousCrossOrigin: false })
        v.start();
        v.ready().then(function(){
            var image = canvas.toDataURL("image/" + imageType);
            localStorage.setItem('mapImg', image);
        });
    }

    function downloadSvg(svg, svgName) {
        var svgElement = getSvgElement(svg);

        if (!svgElement) { return; }
        if (svgName == null) {
            svgName = "chart";
        }

        // -custom images
        var images = svgElement.getElementsByTagName("image");
        var image_promises = [];
        if (images){
            for (var image of images) {
                if ((image.getAttribute("href") && image.getAttribute("href").indexOf("data:") === -1)
                    || (image.getAttribute("xlink:href") && image.getAttribute("xlink:href").indexOf("data:") === -1)) {
                    image_promises.push(convertImageURLtoDataURI(image));
                }
            }
        }

        Promise.all(image_promises).then(function() {
            //get svg string
            var svgString = setupSvg(svgElement, svg);

            //add xml declaration
            svgString = "<?xml version=\"1.0\" standalone=\"no\"?>\r\n" + svgString;

            //convert svg string to URI data scheme.
            var url = "data:image/svg+xml;charset=utf-8," + encodeURIComponent(svgString);

            var decoded = unescape(encodeURIComponent(svgString));
            var img = document.createElement('img');
            var base64 = btoa(decoded);
            var imgSource = `data:image/svg+xml;base64,${base64}`;
            img.setAttribute('src', imgSource);
            document.body.append(img);

            localStorage.setItem('mapImg', imgSource);


            // var link = document.createElement("a");
            // link.download = name;
            // link.href = url;
            // document.body.appendChild(link);
            // link.click();
            // document.body.removeChild(link);
        });
    }

    function getSvgElement(svg) {
        var div = document.createElement("div");
        div.className = "tempdiv-svg-exportJS";

        if (typeof svg === "string") {
            div.insertAdjacentHTML("beforeend", svg.trim());
            svg = div.firstChild;
        }

        if (!svg.nodeType || svg.nodeType !== 1) {
            warnError("Error svg-export: The input svg was not recognized");
            return null;
        }

        var svgClone = svg.cloneNode(true);
        svgClone.style.display = null;
        div.appendChild(svgClone);
        div.style.visibility = "hidden";
        div.style.display = "table";
        div.style.position = "absolute";
        document.body.appendChild(div);

        return svgClone;
    }

    function convertImageURLtoDataURI(image) {
        return new Promise(function(resolve, reject) {
            var newImage = new Image();

            newImage.onload = function () {
                var canvas = document.createElement("canvas");
                canvas.width = this.naturalWidth || this.getAttribute("width") || this.style.getPropertyValue("width") || 300;
                canvas.height = this.naturalHeight || this.getAttribute("height") || this.style.getPropertyValue("height") || 300;

                canvas.getContext("2d").drawImage(this, 0, 0);

                var dataURI = canvas.toDataURL("image/png");
                image.setAttribute("href", dataURI);
                resolve();
            };

            newImage.src = image.getAttribute("href") || image.getAttributeNS("http://www.w3.org/1999/xlink", "href");
        });
    }

    function setupSvg(svgElement, originalSvg, asString)
    {
        if (typeof asString === "undefined") { asString = true; }

        svgElement.style.width = null;
        svgElement.style.height = null;
        svgElement.setAttribute("width", 1200);
        svgElement.setAttribute("height", 851);
        svgElement.setAttribute("preserveAspectRatio", "none");
        svgElement.setAttribute("viewBox", "0 0 " + 1200 + " " + 851);

        var elements = document.getElementsByClassName("tempdiv-svg-exportJS");
        while(elements.length > 0){
            elements[0].parentNode.removeChild(elements[0]);
        }

        //get svg string
        if (asString)
        {
            var serializer = new XMLSerializer();
            //setting currentColor to black matters if computed styles are not used
            var svgString = serializer.serializeToString(svgElement).replace(/currentColor/g, "black");

            //add namespaces
            if (!svgString.match(/^<svg[^>]+xmlns="http\:\/\/www\.w3\.org\/2000\/svg"/)) {
                svgString = svgString.replace(/^<svg/, "<svg xmlns=\"http://www.w3.org/2000/svg\"");
            }
            if (!svgString.match(/^<svg[^>]+"http\:\/\/www\.w3\.org\/1999\/xlink"/)) {
                svgString = svgString.replace(/^<svg/, "<svg xmlns:xlink=\"http://www.w3.org/1999/xlink\"");
            }

            return svgString;
        }
        return svgElement;
    }
})();


