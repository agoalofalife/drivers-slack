<img src="https://fondbot.com/images/logo.png" width="200px">



[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/agoalofalife/drivers-slack/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/agoalofalife/drivers-slack/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/agoalofalife/drivers-slack/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/agoalofalife/drivers-slack/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/agoalofalife/drivers-slack/badges/build.png?b=master)](https://scrutinizer-ci.com/g/agoalofalife/drivers-slack/build-status/master)

## FondBot Slack Driver
This is an official Slack driver for [FondBot](https://github.com/fondbot/fondbot).

## Installation And Usage

Read official Slack Driver usage [documentation](http://docs.fondbot.com/#/drivers/slack).


**Configuration**

Define Facebook channel in src/Providers/ChannelServiceProvider.php:

```
'slack' => [
             'driver' => 'slack',
             'token'  => env('SLACK_TOKEN'),
             'verify_token' => env('SLACK_VERIFY_TOKEN'),
         ]
```

**Templates**

**Button**


```

 $keyboard = (new Keyboard)
            ->addButton(
                (new RequestButton())->setLabel('Phone')->setActivator('recommend')->setConfirm((new RequestConfirmButton()))
            )
           ->addButton(
                (new RequestButton())->setLabel('Mail')
            )
            
        $this->sendMessage('Please select the type of connection', $keyboard);
```


**Select**
```

    $this->sendMessage('Select',  (new RequestSelect())->addOption(  [
                            "text"=> "Global Thermonuclear War",
                            "value"=> "war"
                        ]));
```

