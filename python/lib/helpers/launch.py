from routine import Routine
from tombola.tombola import Tombola

tombola_routine = Routine(30*60, Tombola().check_transaction_routine)
tombola_routine.start()
