
Components.Subbly.Collection.Products = Components.Subbly.Collection.List.extend(
{
    model:       Components.Subbly.Model.Product
  , serviceName: 'products'

  , comparator: function( model )
    {
        return model.get('position')
    }
})