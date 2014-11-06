
Components.Subbly.Model.User = SubblyModel.extend(
{
    idAttribute:  'uid'
  , serviceName:  'users'
  , singleResult: 'user'

  , displayName: function()
    {
      return this.get('firstname') + ' ' + this.get('lastname')
    }
})
