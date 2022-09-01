(() => {
    /*
     * #Олегпоменяй PROPERTY_220[n0][VALUE] на свое свойство
     */
    const property = 'PROPERTY_220[n0][VALUE]';
    const td = document.querySelector(`input[name="${property}"]`).closest('td');
    const button = document.createElement('button');
    button.innerText = 'Создать карту';
    td.append(button);
    button.addEventListener('click', (e) => {
        e.preventDefault();
        BX.SidePanel.Instance.open('/local/components/pgk/map/index.php', {
            cacheable: false,
            data: {
                ID: 1,
            },
            width: 1000,
            events: {
                onCloseComplete: function() {
                    const input = document.querySelector(`input[name="${property}"]`);
                    const dT = new ClipboardEvent('').clipboardData || new DataTransfer();
                    dT.items.add(
                        new File(
                            [dataURItoBlob(localStorage.getItem('mapImg'))], 'map.png', {
                                type: 'image/png'
                            }
                        )
                    )
                    input.files = dT.files;
                }
            }
        });

        function dataURItoBlob(dataURI) {
            var binary = atob(dataURI.split(',')[1]);
            var array = [];
            for(var i = 0; i < binary.length; i++) {
                array.push(binary.charCodeAt(i));
            }
            return new Blob([new Uint8Array(array)], {type: 'image/png'});
        }
    })
})();
