App.use([{
        dependencies: 'cascade',
        callback: function() {
            App.log('Cascade Framework JS layer has been loaded')
        }
    }, {
        dependencies: 'analytics'
    }]);