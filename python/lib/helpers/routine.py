from threading import Thread
import time

class Routine(Thread):
    '''Class to launch parallel thread for routines'''
    def __init__(self, sec, func):
        super().__init__();
        self.delay = sec
        self.func = func
        self.is_done = False

    def done(self):
        self.is_done = True

    def run(self):
        while not self.is_done:
            self.func()
            time.sleep(self.delay)
        print('Routine done.')
