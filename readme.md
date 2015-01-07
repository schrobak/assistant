# Revolve Assistant

[![Status](http://img.shields.io/travis/revolvephp/assistant.svg?style=flat-square)](https://travis-ci.org/revolvephp/assistant)
[![Quality](http://img.shields.io/scrutinizer/g/revolvephp/assistant.svg?style=flat-square)](https://scrutinizer-ci.com/g/revolvephp/assistant)
[![Coverage](http://img.shields.io/scrutinizer/coverage/g/revolvephp/assistant.svg?style=flat-square)](http://revolvephp.github.io/assistant/master)
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](license.md)

## Testing

```sh
$ git@github.com:revolvephp/assistant.git
$ cd assistant
$ vendor/bin/phpunit
```

## Answers

### What is this?

It's an abstraction for non-blocking tools and workflows. It's a wrapper around concurrent, asynchronous extensions like Gearman, IronWorker, pThreads and pcntl_fork.

### Why not use queues?

Queues are a different thing. They are often used to get blocking tasks out of the request/response cycle, but they are uni-directional. There's no way to get progress or completion events.

### What is the messenger for?

Most concurrent, asynchronous extensions don't let you communicate from the worker to the manager. Some allow you to wait until the worker is done, but that's blocking and poorly supported. The messenger allows this communication is a non-blocking way.

### Infinite loops are blocking, chump!

That's not a question. The examples use infinite loops, and these are blocking. For optimal results, you should use Assistant within an event loop. I built it to work with the [framework](https://github.com/revolvephp/framework), but it works like a charm with any [React](https://github.com/reactphp/react) app.