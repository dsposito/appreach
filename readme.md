## Introduction

AppReach is an outreach tool that can be used to promote or garner interest in an app that you're building.

AppReach will perform a search query to find users that are engaged with a @handle, #hashtag or keyword. Second, it will attempt to find the related website URL for each user based on profile data. Finally, it will attempt to find an email address on the user's website. The final output is a list of email contacts to make it easier and faster to ask a bunch of users for feedback on your app!

An API interface is provided using the [Lumen framework](http://lumen.laravel.com/docs).


## Configuration

The easiest setup involves configuring a [Homestead VM](https://laravel.com/docs/5.2/homestead) and editing the application key in the [.env file](https://lumen.laravel.com/docs/5.2).

You'll also need to [create a Twitter Application](https://apps.twitter.com/app/new) and [plugin](https://github.com/dsposito/appreach/blob/master/app/Services/Twitter.php#L19) your API client key and secret values.


## Usage

Let's say you're building a new developer tool that integrates with MailChimp. It probably makes sense to reach out to some developers on Twitter that have recently tweeted about MailChimp. The following API request would respond with a list of relevant contacts (with website and email address information).

	$ curl appreach.app/search?q=@MailChimp

Twitter search param documentation can be found [here](https://dev.twitter.com/rest/public/search).
