PersistentStack
===

A persistent, SQLite-dependent LIFO stack for PHP applications
---

### Introduction

PersistentStack is a simple PHP class for producing and consuming named
stacks of data.

One use-case is dealing with ad-hoc batch jobs that can't fully complete
because they use up all memory. To remedy, make one script that notes down
what has to be processed and places necessary IDs/data on the stack. Then make
another script that draws the necessary IDs/data from the stack in small
batches and does the actual processing. You can run the second script as many
times as gets the job done.

This class is based loosely on the Stack::Persistent Perl module.

### Usage

Here's example usage:

    // Open or create stack: if you don't specify a filename, 'stack.db' will be used
    $stack = new PersistentStack('my_stuff.db');
    
    // Add simple things to a named stack
    $stack->push('clothes', 'socks');
    $stack->push('clothes', 'pants');
    
    // Add something complete to another named stack
    $stack->push('books', array(
      'title'  => 'The Tin Drum',
      'author' => 'Gunter Grass'
    ));
    
    // Output the top of a stack without removing it from the stack
    print $stack->last('clothes');

    // Retrieve the top of a stack, removing it from the stack
    $last_item = $stack->pop('clothes');

See demo.php for more.

### Etc.

Requirement: PHP 5.x with the PDO extension and PDO SQLite driver
Author: Mike Cantelon
License: Gnu Public License (http://www.gnu.org/copyleft/gpl.html)
