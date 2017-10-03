var page = require('webpage').create();

page.viewportSize = {width: 1024, height: 768};





page.open('https://digilockrjit.azurewebsites.net', function (status) {
    if (status !== 'success') {
        console.log('Unable to load the address!');
        phantom.exit(1);
    }

    
    page.evaluate(function() {
                    /* This will set the page background color */
            if (document && document.body) {
                document.body.bgColor = '#FFFFFF';
            }
        
            });

    setTimeout(function() {
            page.render('test.jpg');
            phantom.exit();
    }, 0);
});
