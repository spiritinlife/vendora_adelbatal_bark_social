# Bark Bark bark

The new social network `Bark` has taken the world by storm. What started in the basement of a dog has now become the most viral sensation of humans.
For the first time dogs and humans are able to interact with each other. In the next 5 days the platform is expecting to attract more than 1million users
barking at each other. Is the system ready to grow that much and handle that load? Are there any bugs that need to be fixed? Is the codebase ready so that the development team can increase? Are there any security vulnerabilities that need to be handled?

## Bootbark the project

1. php artisan migrate
2. php artisan db:seed ( will take a while )

Project has been setup to start with sail easily but using sail is not a requirement.

## Issues to fix 

1. Barkers have reported that the site is slow, specially for users with high number of friends. Optimize the code to increase the performance.
2. Barkers have reported that when you have many barks either in your home feed or in your friends feed the data consumption is realy high. Refactor the code to reduce the amount of data is sent. Barkers usually see the first 10 barks in their feed and are not usually scrolling to the bottom.
3. Barkers have reported that the error message ("Input is invalid") displayed when the submitted bark is invalid is not very helpful. How can we improve there and display what actually went wrong with the submitted bark message?
4. Mew_social, the rival social platform that is full of mewers have leaked some data about our current user growth. It seems they have found a way to scrape our data and see the exact amount of barkers that are signed up. How can that be? Can this be fixed asap?

## Goals to achieve

1. The platform should be optimized for reads. We want to make sure that a bark will eventually be displayed to your friends feed. 
Refactor the code so that the friends feed generation has the same response time no matter how many friends or barks a barker has.
2. The platform should be clean from security vulnerubilities. There seems to be a way for users to tamper with their friends feeds. We shouldn't allow that.
3. A new engineer got in the team and is really not pleased with our current codebase. He said that too many things happen in the controllers and that we are not taking advantage of laravel features even in routing. If there are any improvements in structure or separation of concerns, you are more than welcome to execute them. 

### ⚠️ Important ⚠️
```
You are free to add or utilise any extra database that you may need in order to optimize the platform for the expected high feed reads.
```

## Validation rules of bark messages

1. A bark cannot be more than 500 characters
2. A bark must be at least 4 characters
3. If the bark message is 4 characters then it can only be the word "Bark"

Ensure that all these conditions are met!


## Submission

You should fork the project and submit a pr with your changes when you are done.

Good luck :)
Or as our barkers say Bark bark, bark Bark!
