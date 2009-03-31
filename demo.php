<?php

include('persistent_stack.inc.php');

function show_size_of_stacks($stack) {

  print 'Overall stack size: '. $stack->size() ."\n";
  print 'Clothing stack size: '. $stack->size('clothes') ."\n";
  print 'Book stack size: '. $stack->size('books') ."\n\n";
}

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

show_size_of_stacks($stack);

// Retrieve the top of a stack without removing it from the stack
print 'Last clothing item added: '. $stack->last('clothes') ."\n";
print 'Clothing stack size: '. $stack->size('clothes') ."\n";

// Retrieve the top of a stack, removing it from the stack
print 'I am removing the clothing item '. $stack->pop('clothes') ."!\n\n";

show_size_of_stacks($stack);

// Clear clothing stack
$stack->clear('clothes');
print "I have cleared the clothing stack!\n\n";

show_size_of_stacks($stack);

// Clear everything
$stack->clear();
print "I have cleared all stacks!\n\n";

show_size_of_stacks($stack);

?>
