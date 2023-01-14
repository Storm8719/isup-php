class Queue {
    queue = {};
    tail = 0;
    head = 0;
    length = 0;

    enqueue = (element) => {
        this.queue[this.tail++] = element;
        this.length++;
    }
    dequeue = () => {
        if (this.tail === this.head)
            return false
        const element = this.queue[this.head];
        delete this.queue[this.head++];
        this.length--;
        return element;
    }
}

module.exports = Queue;