<?php

/**
 * Class TestOptionController
 */
class TestOptionController extends BaseController
{
    public function __construct()
    {
        Subbly\Shop::initialize();

        Subbly\Shop::registerOption('admin_mail', [
            'group' => 'general',
            'subgroup' => 'important',
            'type' => 'email',
            'validation' => 'required',
        ]);

        Subbly\Shop::registerOption('siret', [
            'group' => 'legal',
            'subgroup' => 'super',
            'type' => 'number',
            'validation' => 'required',
        ]);

        Subbly\Shop::registerOption('description', [
            'group' => 'general',
            'subgroup' => 'secondary',
            'type' => 'textarea',
            'validation' => 'required',
        ]);

        Subbly\Shop::registerOption('country', [
            'group' => 'general',
            'subgroup' => 'important',
            'type' => 'select',
            'values' => [
                'fr' => 'France',
                'en' => 'England',
                'es' => 'Spain',
            ],
        ]);
    }

    public function index()
    {
        $errors = Session::get('errors');

        if( !is_null($errors) )
        {
            return $errors->getMessages();
        }

        #TODO return Subbly\Shop::getGroupedOptions();

        $form = '';

        $form .= Subbly\Shop\OptionForm::input('admin_mail');
        $form .= Subbly\Shop\OptionForm::input('siret');
        $form .= Subbly\Shop\OptionForm::input('description');
        $form .= Subbly\Shop\OptionForm::input('country');

        return Form::open(['action' => 'TestOptionController@save']) . $form . Form::submit('Save') . Form::close();
    }

    public function save()
    {
        $options = $_POST['options'];

        $validator = Subbly\Shop\OptionForm::validate($options);

        if( $validator->fails() )
        {
            return Redirect::action('TestOptionController@index')->withErrors($validator);
        }

        Subbly\Shop::setOptions($_POST['options']);

        return $_POST['options'];
    }
}