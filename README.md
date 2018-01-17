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
  - To do SMS, use the `?type` variable like this:
    - Voice Webhook: `http://your.server.here/time-temp.php?type=voice`
    - SMS Webhook: `http://your.server.here/time-temp.php?type=sms`
- Call/text the number to test!
