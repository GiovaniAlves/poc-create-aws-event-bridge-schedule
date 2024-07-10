<?php

namespace App\Http\Controllers;

use Aws\EventBridge\EventBridgeClient;
use Aws\Scheduler\SchedulerClient;
use Aws\Exception\AwsException;

class AppController
{
    public function index()
    {
        // Create a SchedulerClient
        $client = new SchedulerClient([
            'region' => 'us-west-2',
            'version' => 'latest',
            'credentials' => [
                'key' => '**********',
                'secret' => '********',
            ],
        ]);

        try {
            // Create a schedule
            $result = $client->createSchedule([
                'Name' => 'my-schedule',  // required, name of the schedule
                'ScheduleExpression' => 'rate(5 minutes)',  // required, schedule expression
                'FlexibleTimeWindow' => [
                    'Mode' => 'OFF',  // required, 'OFF' or 'FLEXIBLE'
                ],
                'Target' => [  // required, target details
                    'Arn' => 'arn:aws:lambda:us-west-2:012115122060:function:myTestFunction',
                    'RoleArn' => 'arn:aws:iam::012115122060:role/eventBridgeScheduleRole',
                ],
            ]);
            echo "Schedule created successfully. Schedule ARN: " . $result['ScheduleArn'] . "\n";
        } catch (AwsException $e) {
            // Output error message if fails
            echo $e->getMessage();
            echo "\n";
        }
    }
}
