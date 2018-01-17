# Twilio Time & Temperature
Make your own Time and Temperature phone number / sms service!

## Prerequisites

- A Twilio account
- A Weather Underground API account
- A publicly-accessible server running PHP

## Installation

- Copy the time-temp.inc.php.dist file to time-temp.inc.php and modify with your Twilio and WUnderground details.
- Access time-temp.php to ensure it's working (should generate XML.)
- Set your Twilio phone number(s) to send an HTTP POST WebHook to the time-temp.php public URL.
- Call/text the number to test!
