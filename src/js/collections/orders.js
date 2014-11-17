
Components.Subbly.Collection.Orders = Components.Subbly.Collection.List.extend(
{
    model:       Components.Subbly.Model.Order
  , serviceName: 'orders'

  , comparator: function( model )
    {
        return model.get('id')
    }
})