<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            box-sizing: border-box;
            cursor: none;
        }

        :root {
            --cursor-1-bg: #ffffff;
            --cursor-2-bg: #151a1f;
            --cursor-3-bg: #f1f68f;
            --cursor-4-bg: #b0efda;
            --cursor-5-bg: #d5c9fb;
            --cursor-6-bg: #ffbc90;
            --cursor-1-bg-line: #eef1f4;
            --cursor-2-bg-line: #1f272e;
            --cursor-3-bg-line: #f1f68f;
            --cursor-4-bg-line: #b0efda;
            --cursor-5-bg-line: #d5c9fb;
            --cursor-6-bg-line: #ffbc90;

            --curzr-logo-color: #292927;

            .body {
                position: relative;
                width: 100%;
                font-family: 'Plus Jakarta Sans', Arial, Helvetica, sans-serif;
                font-weight: bold;
                font-size: .875rem;
                overflow: hidden;
                color: #292927;

                    .cursorSpan {
                        overflow: hidden;
                        transition: 250ms;
                        user-select: none;

                        &:hover {
                            opacity: .75;
                        }

                        &>* {
                            display: inline-block;
                        }
                    }

                    svg {
                        width: 75px;

                        .cls-1 {
                            fill: var(--curzr-logo-color);
                        }
                    }
                }

                /* .container {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100vh;
                    background-size: 40px 80px;
                    background-position: center;
                    background-attachment: fixed;
                } */

                .container-cursor-1 {
                    z-index: 6;
                    background-color: var(--cursor-1-bg);
                    background-image:
                        linear-gradient(var(--cursor-1-bg-line) 1px, transparent 1px),
                        linear-gradient(to right, var(--cursor-1-bg-line) 1px, var(--cursor-1-bg) 1px);
                }

                .container-cursor-2 {
                    z-index: 5;
                    background-color: var(--cursor-2-bg);
                    background-image:
                        linear-gradient(var(--cursor-2-bg-line) 1px, transparent 1px),
                        linear-gradient(to right, var(--cursor-2-bg-line) 1px, var(--cursor-2-bg) 1px);
                }

                .container-cursor-3 {
                    z-index: 4;
                    background-color: var(--cursor-3-bg);
                    background-image:
                        linear-gradient(var(--cursor-3-bg-line) 1px, transparent 1px),
                        linear-gradient(to right, var(--cursor-3-bg-line) 1px, var(--cursor-3-bg) 1px);
                }

                .container-cursor-4 {
                    z-index: 3;
                    background-color: var(--cursor-4-bg);
                    background-image:
                        linear-gradient(var(--cursor-4-bg-line) 1px, transparent 1px),
                        linear-gradient(to right, var(--cursor-4-bg-line) 1px, var(--cursor-4-bg) 1px);
                }

                .container-cursor-5 {
                    z-index: 2;
                    background-color: var(--cursor-5-bg);
                    background-image:
                        linear-gradient(var(--cursor-5-bg-line) 1px, transparent 1px),
                        linear-gradient(to right, var(--cursor-5-bg-line) 1px, var(--cursor-5-bg) 1px);
                }

                .container-cursor-6 {
                    z-index: 1;
                    background-color: var(--cursor-6-bg);
                    background-image:
                        linear-gradient(var(--cursor-6-bg-line) 1px, transparent 1px),
                        linear-gradient(to right, var(--cursor-6-bg-line) 1px, var(--cursor-6-bg) 1px);
                }

                /* footer {
                    position: fixed;
                    display: flex;
                    bottom: 0;
                    justify-content: space-between;
                    width: 100%;
                    z-index: 7;
                    padding: 1.5rem 2rem;
                    line-height: 1.25;

                    /* .cursorSpan {
                        overflow: hidden;
                        user-select: none;

                        &>* {
                            display: inline-block;
                        }
                    } */
                } */

                small.shift-in {
                    position: relative;

                    &::after {
                        content: attr(data-text);
                        position: absolute;
                        bottom: -100%;
                        left: 0;
                    }
                }
            }
        }
        @media (max-width: 1079px) {
    /* Adjust cursor sizes for smaller screens */
    .curzr-big-circle .circle {
        width: 30px !important;
        height: 30px !important;
        top: -15px !important;
        left: -15px !important;
    }
    
    /* Make sure the footer is properly positioned */
    footer {
        padding: 1rem !important;
    }
    
    /* Adjust SVG sizes */
    svg {
        width: 50px !important;
    }
    
    /* Prevent overflow issues */
    body {
        overflow-x: hidden;
    }
}
    </style>
</head>
<body class"body">
    <footer>
        <span class="cursorSpan curzr-hover btn btn-previous" ></span>
        <span></span>
        <span class="cursorSpan curzr-hover btn btn-next"></span>
    </footer>

    <div class="curzr-arrow-pointer" hidden>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
            <path class="inner"
                d="M25,30a5.82,5.82,0,0,1-1.09-.17l-.2-.07-7.36-3.48a.72.72,0,0,0-.35-.08.78.78,0,0,0-.33.07L8.24,29.54a.66.66,0,0,1-.2.06,5.17,5.17,0,0,1-1,.15,3.6,3.6,0,0,1-3.29-5L12.68,4.2a3.59,3.59,0,0,1,6.58,0l9,20.74A3.6,3.6,0,0,1,25,30Z"
                fill="#F2F5F8" />
            <path class="outer"
                d="M16,3A2.59,2.59,0,0,1,18.34,4.6l9,20.74A2.59,2.59,0,0,1,25,29a5.42,5.42,0,0,1-.86-.15l-7.37-3.48a1.84,1.84,0,0,0-.77-.17,1.69,1.69,0,0,0-.73.16l-7.4,3.31a5.89,5.89,0,0,1-.79.12,2.59,2.59,0,0,1-2.37-3.62L13.6,4.6A2.58,2.58,0,0,1,16,3m0-2h0A4.58,4.58,0,0,0,11.76,3.8L2.84,24.33A4.58,4.58,0,0,0,7,30.75a6.08,6.08,0,0,0,1.21-.17,1.87,1.87,0,0,0,.4-.13L16,27.18l7.29,3.44a1.64,1.64,0,0,0,.39.14A6.37,6.37,0,0,0,25,31a4.59,4.59,0,0,0,4.21-6.41l-9-20.75A4.62,4.62,0,0,0,16,1Z"
                fill="#111920" />
        </svg>
    </div>

    <div class="curzr-big-circle" hidden>
        <div class="circle"></div>
        <div class="dot"></div>
    </div>

    <div class="curzr-ring-dot" hidden>
        <div class="curzr-dot"></div>
    </div>

    <div class="curzr-circle-and-dot" hidden></div>

    <div class="curzr-glitch-effect" hidden></div>

    <svg class="curzr-motion" hidden>
        <filter id="motionblur" x="-100%" y="-100%" width="400%" height="400%">
            <feGaussianBlur class="curzr-motion-blur" stdDeviation="0, 0" />
        </filter>
        <circle cx="50%" cy="50%" r="5" fill="#292927" filter="url(#motionblur)" />
    </svg>
    <script>
        WebFont.load({
            google: {
                families: ['Plus Jakarta Sans']
            }
        });
    </script>
    <script>
        document.ontouchmove = function (event) {
    cursor.move(event.touches[0])
}
         let sectionName = "cursor-1"
        let sectionPrev = ""
        let sectionList = [
            "cursor-1",
            "cursor-2",
            "cursor-3",
            "cursor-4",
            "cursor-5",
            "cursor-6"
        ]
        let cursorList = [
            "arrow-pointer",
            "big-circle",
            "ring-dot",
            "circle-and-dot",
            "glitch-effect",
            "motion-blur"
        ]
        let isShiftDone = false

        const btnPrevious = document.querySelector(".btn-previous")
        const btnNext = document.querySelector(".btn-next")
        const header = document.querySelector("header")
        const footer = document.querySelector("footer")
        const root = document.querySelector(':root')

        window.onload = function () {
            shiftIn()
        }

        btnPrevious.addEventListener('click', function () {
            if (isShiftDone) {
                location.href = "#" + sectionList[(sectionList.indexOf(sectionName) + sectionList.length - 1) % sectionList.length]
            }
        })

        btnNext.addEventListener('click', function () {
            if (isShiftDone) {
                location.href = "#" + sectionList[(sectionList.indexOf(sectionName) + 1) % sectionList.length]
            }
        })

        btnPrevious.addEventListener('mouseenter', function () {
            shiftUp('.btn-previous small.shift-in')
        })

        btnNext.addEventListener('mouseenter', function () {
            shiftUp('.btn-next small.shift-in')
        })

        let shiftup = setInterval(() => {
            shiftUp('.btn-next small.shift-in')
        }, 3000)

        window.addEventListener('popstate', function () {
            sectionPrev = sectionName
            sectionName = getAnchor()
            this.clearInterval(shiftup)
            pageChange(sectionName, sectionPrev)
        })

        function shiftIn() {
            anime({
                targets: '.shift-in',
                translateY: ['50px', '0'],
                delay: anime.stagger(100),
                easing: 'easeInOutSine',
                complete: function () {
                    isShiftDone = true
                }
            })

            if (sectionName !== "cursor-2") {
                anime({
                    targets: '.shift-in',
                    color: '#292927',
                    delay: anime.stagger(100)
                })
                root.style.setProperty('--curzr-logo-color', '#292927')
            } else {
                anime({
                    targets: '.shift-in',
                    color: '#e6e6e6',
                    delay: anime.stagger(100)
                })
                root.style.setProperty('--curzr-logo-color', '#e6e6e6')
            }
        }

        function shiftUp(el) {
            anime({
                targets: el,
                translateY: ['0%', '-100%'],
                duration: 500,
                delay: anime.stagger(100),
                easing: 'easeInOutCubic',
                complete: function () {
                }
            })
        }

        function getAnchor() {
            let anchor = document.URL.split('#')[1]
            return anchor ? anchor : null
        }

        function pageChange(sectionName, sectionPrev) {
            isShiftDone = false
            let duration = 1000
            let sectionIndex = sectionList.findIndex((section) => section === sectionName)
            changeCursor(sectionIndex)

            document.getElementById(sectionName).style.zIndex = sectionList.length + 2
            document.getElementById(sectionPrev).style.zIndex = sectionList.length

            anime({
                targets: document.getElementById(sectionName),
                translateX: ['-100%', '0%'],
                easing: 'easeInOutCirc'
            })

            anime({
                targets: document.getElementById(sectionPrev),
                translateX: ['0%', '100%'],
                duration: duration,
                easing: 'easeInOutCirc',
                complete: function () {
                    document.getElementById(sectionPrev).style.transform = 'translateX(0%)'
                    document.getElementById(sectionPrev).style.zIndex = sectionList.length - sectionList.indexOf(sectionPrev)
                    shiftup = setInterval(() => {
                        shiftUp('.btn-next small.shift-in')
                    }, 3000)
                }
            })

            anime({
                targets: [header, footer],
                translateX: ['0%', '50%'],
                duration: duration,
                easing: 'easeInCirc',
                complete: function () {
                    document.getElementById(sectionName).style.zIndex = sectionList.length
                    header.style.transform = 'translateX(0%)'
                    footer.style.transform = 'translateX(0%)'
                    shiftIn()
                }
            })
        }

        function changeCursor(index) {
            cursor.hidden()
            switch (cursorList[index]) {
                case 'arrow-pointer':
                    cursor = new ArrowPointer()
                    break
                case 'big-circle':
                    cursor = new BigCircle()
                    break
                case 'ring-dot':
                    cursor = new RingDot()
                    break
                case 'circle-and-dot':
                    cursor = new CircleAndDot()
                    break
                case 'glitch-effect':
                    cursor = new GlitchEffect()
                    break
                case 'motion-blur':
                    cursor = new MotionBlur()
                    break
            }
        }

        class ArrowPointer {
            constructor() {
                this.root = document.body
                this.cursor = document.querySelector(".curzr-arrow-pointer")

                this.position = {
                    distanceX: 0,
                    distanceY: 0,
                    distance: 0,
                    pointerX: 0,
                    pointerY: 0,
                },
                    this.previousPointerX = 0
                this.previousPointerY = 0
                this.angle = 0
                this.previousAngle = 0
                this.angleDisplace = 0
                this.degrees = 57.296
                this.cursorSize = 20

                this.cursorStyle = {
                    boxSizing: 'border-box',
                    position: 'fixed',
                    top: '50%',
                    left: '50%',
                    transform: 'translate(-50%, -50%)',
                    zIndex: '2147483647',
                    width: `${this.cursorSize}px`,
                    height: `${this.cursorSize}px`,
                    transition: '250ms, transform 100ms',
                    userSelect: 'none',
                    pointerEvents: 'none'
                }

                this.init(this.cursor, this.cursorStyle)
            }

            init(el, style) {
                Object.assign(el.style, style)
                setTimeout(() => {
                    this.cursor.removeAttribute("hidden")
                }, 500)
                this.cursor.style.opacity = 1
            }

            move(event) {
                this.previousPointerX = this.position.pointerX
                this.previousPointerY = this.position.pointerY
                this.position.pointerX = event.pageX + this.root.getBoundingClientRect().x
                this.position.pointerY = event.pageY + this.root.getBoundingClientRect().y
                this.position.distanceX = this.previousPointerX - this.position.pointerX
                this.position.distanceY = this.previousPointerY - this.position.pointerY
                this.distance = Math.sqrt(this.position.distanceY ** 2 + this.position.distanceX ** 2)

                this.cursor.style.transform = `translate3d(${this.position.pointerX}px, ${this.position.pointerY}px, 0)`

                if (this.distance > 1) {
                    this.rotate(this.position)
                } else {
                    this.cursor.style.transform += ` rotate(${this.angleDisplace}deg)`
                }
            }

            rotate(position) {
                let unsortedAngle = Math.atan(Math.abs(position.distanceY) / Math.abs(position.distanceX)) * this.degrees
                let modAngle
                const style = this.cursor.style
                this.previousAngle = this.angle

                if (position.distanceX <= 0 && position.distanceY >= 0) {
                    this.angle = 90 - unsortedAngle + 0
                } else if (position.distanceX < 0 && position.distanceY < 0) {
                    this.angle = unsortedAngle + 90
                } else if (position.distanceX >= 0 && position.distanceY <= 0) {
                    this.angle = 90 - unsortedAngle + 180
                } else if (position.distanceX > 0 && position.distanceY > 0) {
                    this.angle = unsortedAngle + 270
                }

                if (isNaN(this.angle)) {
                    this.angle = this.previousAngle
                } else {
                    if (this.angle - this.previousAngle <= -270) {
                        this.angleDisplace += 360 + this.angle - this.previousAngle
                    } else if (this.angle - this.previousAngle >= 270) {
                        this.angleDisplace += this.angle - this.previousAngle - 360
                    } else {
                        this.angleDisplace += this.angle - this.previousAngle
                    }
                }
                style.left = `${-this.cursorSize / 2}px`
                style.top = `${0}px`
                style.transform += ` rotate(${this.angleDisplace}deg)`
            }

            hidden() {
                this.cursor.style.opacity = 0
                setTimeout(() => {
                    this.cursor.setAttribute("hidden", "hidden")
                }, 500)
            }
        }

        class BigCircle {
            constructor() {
                this.root = document.body
                this.cursor = document.querySelector(".curzr-big-circle")
                this.circle = document.querySelector(".curzr-big-circle .circle")
                this.dot = document.querySelector(".curzr-big-circle .dot")

                this.pointerX = 0
                this.pointerY = 0
                this.cursorSize = 50

                this.circleStyle = {
                    boxSizing: 'border-box',
                    position: 'fixed',
                    top: `${this.cursorSize / -2}px`,
                    left: `${this.cursorSize / -2}px`,
                    zIndex: '2147483647',
                    width: `${this.cursorSize}px`,
                    height: `${this.cursorSize}px`,
                    backgroundColor: '#fff0',
                    borderRadius: '50%',
                    transition: '500ms, transform 100ms',
                    userSelect: 'none',
                    pointerEvents: 'none'
                }

                this.dotStyle = {
                    boxSizing: 'border-box',
                    position: 'fixed',
                    zIndex: '2147483647',
                    width: '6px',
                    height: '6px',
                    backgroundColor: '#0000',
                    borderRadius: '50%',
                    userSelect: 'none',
                    pointerEvents: 'none',
                    transition: '250ms, transform 75ms'
                }

                if (CSS.supports("backdrop-filter", "invert(1) grayscale(1)")) {
                    this.circleStyle.backdropFilter = 'invert(0.85) grayscale(1)'
                    this.dotStyle.backdropFilter = 'invert(1)'
                    this.circleStyle.backgroundColor = '#fff0'
                } else {
                    this.circleStyle.backgroundColor = '#000'
                    this.circleStyle.opacity = '0.5'
                }

                this.init(this.circle, this.circleStyle)
                this.init(this.dot, this.dotStyle)
            }

            init(el, style) {
                Object.assign(el.style, style)
                setTimeout(() => {
                    this.cursor.removeAttribute("hidden")
                }, 500)
                this.cursor.style.opacity = 1
            }

            move(event) {
                this.pointerX = event.pageX
                this.pointerY = event.pageY + this.root.getBoundingClientRect().y

                this.circle.style.transform = `translate3d(${this.pointerX}px, ${this.pointerY}px, 0)`
                this.dot.style.transform = `translate3d(calc(-50% + ${this.pointerX}px), calc(-50% + ${this.pointerY}px), 0)`

                if (event.target.localName === 'svg' ||
                    event.target.localName === 'a' ||
                    event.target.onclick !== null ||
                    Array.from(event.target.classList).includes('curzr-hover')) {
                    this.hover()
                }
            }

            hover() {
                this.circle.style.transform += ` scale(2.5)`
            }

            click() {
                this.circle.style.transform += ` scale(0.75)`
                setTimeout(() => {
                    this.circle.style.transform = this.circle.style.transform.replace(` scale(0.75)`, '')
                }, 35)
            }

            hidden() {
                this.cursor.style.opacity = 0
                setTimeout(() => {
                    this.cursor.setAttribute("hidden", "hidden")
                }, 500)
            }
        }

        class RingDot {
            constructor() {
                this.root = document.body
                this.cursor = document.querySelector(".curzr-ring-dot")
                this.dot = document.querySelector(".curzr-ring-dot .curzr-dot")

                this.pointerX = 0
                this.pointerY = 0
                this.cursorSize = 20

                this.cursorStyle = {
                    boxSizing: 'border-box',
                    position: 'fixed',
                    display: 'flex',
                    top: `${this.cursorSize / -2}px`,
                    left: `${this.cursorSize / -2}px`,
                    zIndex: '2147483647',
                    justifyContent: 'center',
                    alignItems: 'center',
                    width: `${this.cursorSize}px`,
                    height: `${this.cursorSize}px`,
                    backgroundColor: '#fff0',
                    boxShadow: '0 0 0 1.25px #292927, 0 0 0 2.25px #edf370',
                    borderRadius: '50%',
                    transition: '200ms, transform 100ms',
                    userSelect: 'none',
                    pointerEvents: 'none'
                }

                this.dotStyle = {
                    boxSizing: 'border-box',
                    position: 'fixed',
                    zIndex: '2147483647',
                    width: '4px',
                    height: '4px',
                    backgroundColor: '#292927',
                    boxShadow: '0 0 0 1px #edf370',
                    borderRadius: '50%',
                    userSelect: 'none',
                    pointerEvents: 'none',
                }

                this.init(this.cursor, this.cursorStyle)
                this.init(this.dot, this.dotStyle)
            }

            init(el, style) {
                Object.assign(el.style, style)
                setTimeout(() => {
                    this.cursor.removeAttribute("hidden")
                }, 500)
                this.cursor.style.opacity = 1
            }

            move(event) {
                if (event.target.localName === 'svg' ||
                    event.target.localName === 'a' ||
                    event.target.onclick !== null ||
                    Array.from(event.target.classList).includes('curzr-hover')) {
                    this.hover(40)
                } else {
                    this.hoverout()
                }

                this.pointerX = event.pageX + this.root.getBoundingClientRect().x
                this.pointerY = event.pageY + this.root.getBoundingClientRect().y

                this.cursor.style.transform = `translate3d(${this.pointerX}px, ${this.pointerY}px, 0)`
            }

            hover(radius) {
                this.cursor.style.width = this.cursor.style.height = `${radius}px`
                this.cursor.style.top = this.cursor.style.left = `${radius / -2}px`
            }

            hoverout() {
                this.cursor.style.width = this.cursor.style.height = `${this.cursorSize}px`
                this.cursor.style.top = this.cursor.style.left = `${this.cursorSize / -2}px`
            }

            click() {
                this.cursor.style.transform += ` scale(0.75)`
                setTimeout(() => {
                    this.cursor.style.transform = this.cursor.style.transform.replace(` scale(0.75)`, '')
                }, 35)
            }

            hidden() {
                this.cursor.style.opacity = 0
                setTimeout(() => {
                    this.cursor.setAttribute("hidden", "hidden")
                }, 500)
            }
        }

        class CircleAndDot {
            constructor() {
                this.root = document.body
                this.cursor = document.querySelector(".curzr-circle-and-dot")

                this.position = {
                    distanceX: 0,
                    distanceY: 0,
                    distance: 0,
                    pointerX: 0,
                    pointerY: 0,
                },
                    this.previousPointerX = 0
                this.previousPointerY = 0
                this.angle = 0
                this.previousAngle = 0
                this.angleDisplace = 0
                this.degrees = 57.296
                this.cursorSize = 20
                this.fading = false

                this.cursorStyle = {
                    boxSizing: 'border-box',
                    position: 'fixed',
                    top: `${this.cursorSize / -2}px`,
                    left: `${this.cursorSize / -2}px`,
                    zIndex: '2147483647',
                    width: `${this.cursorSize}px`,
                    height: `${this.cursorSize}px`,
                    backgroundColor: '#fff0',
                    border: '1.25px solid #292927',
                    borderRadius: '50%',
                    boxShadow: '0 -15px 0 -8px #292927',
                    transition: '250ms, transform 100ms',
                    userSelect: 'none',
                    pointerEvents: 'none'
                }

                this.init(this.cursor, this.cursorStyle)
            }

            init(el, style) {
                Object.assign(el.style, style)
                setTimeout(() => {
                    this.cursor.removeAttribute("hidden")
                }, 500)
                this.cursor.style.opacity = 1
            }

            move(event) {
                this.previousPointerX = this.position.pointerX
                this.previousPointerY = this.position.pointerY
                this.position.pointerX = event.pageX + this.root.getBoundingClientRect().x
                this.position.pointerY = event.pageY + this.root.getBoundingClientRect().y
                this.position.distanceX = this.previousPointerX - this.position.pointerX
                this.position.distanceY = this.previousPointerY - this.position.pointerY
                this.distance = Math.sqrt(this.position.distanceY ** 2 + this.position.distanceX ** 2)

                if (event.target.localName === 'svg' ||
                    event.target.localName === 'a' ||
                    event.target.onclick !== null ||
                    Array.from(event.target.classList).includes('curzr-hover')) {
                    this.hover()
                } else {
                    this.hoverout()
                }

                this.cursor.style.transform = `translate3d(${this.position.pointerX}px, ${this.position.pointerY}px, 0)`

                this.rotate(this.position)
                this.fade(this.distance)
            }

            rotate(position) {
                let unsortedAngle = Math.atan(Math.abs(position.distanceY) / Math.abs(position.distanceX)) * this.degrees
                this.previousAngle = this.angle

                if (position.distanceX <= 0 && position.distanceY >= 0) {
                    this.angle = 90 - unsortedAngle + 0
                } else if (position.distanceX < 0 && position.distanceY < 0) {
                    this.angle = unsortedAngle + 90
                } else if (position.distanceX >= 0 && position.distanceY <= 0) {
                    this.angle = 90 - unsortedAngle + 180
                } else if (position.distanceX > 0 && position.distanceY > 0) {
                    this.angle = unsortedAngle + 270
                }

                if (isNaN(this.angle)) {
                    this.angle = this.previousAngle
                } else {
                    if (this.angle - this.previousAngle <= -270) {
                        this.angleDisplace += 360 + this.angle - this.previousAngle
                    } else if (this.angle - this.previousAngle >= 270) {
                        this.angleDisplace += this.angle - this.previousAngle - 360
                    } else {
                        this.angleDisplace += this.angle - this.previousAngle
                    }
                }
                this.cursor.style.transform += ` rotate(${this.angleDisplace}deg)`
            }

            hover() {
                this.cursor.style.border = '10px solid #292927'
            }

            hoverout() {
                this.cursor.style.border = '1.25px solid #292927'
            }

            fade(distance) {
                this.cursor.style.boxShadow = `0 ${-15 - distance}px 0 -8px #292927`
                if (!this.fading) {
                    this.fading = true
                    setTimeout(() => {
                        this.cursor.style.boxShadow = '0 -15px 0 -8px #29292700'
                        this.fading = false
                    }, 50)
                }
            }

            click() {
                this.cursor.style.transform += ` scale(0.75)`
                setTimeout(() => {
                    this.cursor.style.transform = this.cursor.style.transform.replace(` scale(0.75)`, '')
                }, 35)
            }

            hidden() {
                this.cursor.style.opacity = 0
                setTimeout(() => {
                    this.cursor.setAttribute("hidden", "hidden")
                }, 500)
            }
        }

        class GlitchEffect {
            constructor() {
                this.root = document.body
                this.cursor = document.querySelector(".curzr-glitch-effect")

                this.distanceX = 0,
                    this.distanceY = 0,
                    this.pointerX = 0,
                    this.pointerY = 0,
                    this.previousPointerX = 0
                this.previousPointerY = 0
                this.cursorSize = 15
                this.glitchColorB = '#00feff'
                this.glitchColorR = '#ff4f71'

                this.cursorStyle = {
                    boxSizing: 'border-box',
                    position: 'fixed',
                    top: `${this.cursorSize / -2}px`,
                    left: `${this.cursorSize / -2}px`,
                    zIndex: '2147483647',
                    width: `${this.cursorSize}px`,
                    height: `${this.cursorSize}px`,
                    backgroundColor: '#222',
                    borderRadius: '50%',
                    boxShadow: `0 0 0 ${this.glitchColorB}, 0 0 0 ${this.glitchColorR}`,
                    transition: '100ms, transform 100ms',
                    userSelect: 'none',
                    pointerEvents: 'none'
                }

                if (CSS.supports("backdrop-filter", "invert(1)")) {
                    this.cursorStyle.backdropFilter = 'invert(1)'
                    this.cursorStyle.backgroundColor = '#fff0'
                } else {
                    this.cursorStyle.backgroundColor = '#222'
                }

                this.init(this.cursor, this.cursorStyle)
            }

            init(el, style) {
                Object.assign(el.style, style)
                setTimeout(() => {
                    this.cursor.removeAttribute("hidden")
                }, 500)
                this.cursor.style.opacity = 1

            }

            move(event) {
                this.previousPointerX = this.pointerX
                this.previousPointerY = this.pointerY
                this.pointerX = event.pageX + this.root.getBoundingClientRect().x
                this.pointerY = event.pageY + this.root.getBoundingClientRect().y
                this.distanceX = Math.min(Math.max(this.previousPointerX - this.pointerX, -10), 10)
                this.distanceY = Math.min(Math.max(this.previousPointerY - this.pointerY, -10), 10)

                if (event.target.localName === 'svg' ||
                    event.target.localName === 'a' ||
                    event.target.onclick !== null ||
                    Array.from(event.target.classList).includes('curzr-hover')) {
                    this.hover()
                } else {
                    this.hoverout()
                }

                this.cursor.style.transform = `translate3d(${this.pointerX}px, ${this.pointerY}px, 0)`
                this.cursor.style.boxShadow = `
      ${+this.distanceX}px ${+this.distanceY}px 0 ${this.glitchColorB}, 
      ${-this.distanceX}px ${-this.distanceY}px 0 ${this.glitchColorR}`
                this.stop()
            }

            hover() {
                this.cursorSize = 30
            }

            hoverout() {
                this.cursorSize = 15
            }

            click() {
                this.cursor.style.transform += ` scale(0.75)`
                setTimeout(() => {
                    this.cursor.style.transform = this.cursor.style.transform.replace(` scale(0.75)`, '')
                }, 35)
            }

            stop() {
                if (!this.moving) {
                    this.moving = true
                    setTimeout(() => {
                        this.cursor.style.boxShadow = ''
                        this.moving = false
                    }, 50)
                }
            }

            hidden() {
                this.cursor.style.opacity = 0
                setTimeout(() => {
                    this.cursor.setAttribute("hidden", "hidden")
                }, 500)
            }
        }

        class MotionBlur {
            constructor() {
                this.root = document.body
                this.cursor = document.querySelector(".curzr-motion")
                this.filter = document.querySelector(".curzr-motion .curzr-motion-blur")

                this.position = {
                    distanceX: 0,
                    distanceY: 0,
                    pointerX: 0,
                    pointerY: 0,
                },
                    this.previousPointerX = 0
                this.previousPointerY = 0
                this.angle = 0
                this.previousAngle = 0
                this.angleDisplace = 0
                this.degrees = 57.296
                this.cursorSize = 15
                this.moving = false

                this.cursorStyle = {
                    boxSizing: 'border-box',
                    position: 'fixed',
                    top: `${this.cursorSize / -2}px`,
                    left: `${this.cursorSize / -2}px`,
                    zIndex: '2147483647',
                    width: `${this.cursorSize}px`,
                    height: `${this.cursorSize}px`,
                    borderRadius: '50%',
                    overflow: 'visible',
                    transition: '200ms, transform 20ms',
                    userSelect: 'none',
                    pointerEvents: 'none'
                }

                this.init(this.cursor, this.cursorStyle)
            }

            init(el, style) {
                Object.assign(el.style, style)
                setTimeout(() => {
                    this.cursor.removeAttribute("hidden")
                }, 500)
                this.cursor.style.opacity = 1
            }

            move(event) {
                this.previousPointerX = this.position.pointerX
                this.previousPointerY = this.position.pointerY
                this.position.pointerX = event.pageX + this.root.getBoundingClientRect().x
                this.position.pointerY = event.pageY + this.root.getBoundingClientRect().y
                this.position.distanceX = Math.min(Math.max(this.previousPointerX - this.position.pointerX, -20), 20)
                this.position.distanceY = Math.min(Math.max(this.previousPointerY - this.position.pointerY, -20), 20)

                this.cursor.style.transform = `translate3d(${this.position.pointerX}px, ${this.position.pointerY}px, 0)`
                this.rotate(this.position)
                this.moving ? this.stop() : this.moving = true
            }

            rotate(position) {
                let unsortedAngle = Math.atan(Math.abs(position.distanceY) / Math.abs(position.distanceX)) * this.degrees

                if (isNaN(unsortedAngle)) {
                    this.angle = this.previousAngle
                } else {
                    if (unsortedAngle <= 45) {
                        if (position.distanceX * position.distanceY >= 0) {
                            this.angle = +unsortedAngle
                        } else {
                            this.angle = -unsortedAngle
                        }
                        this.filter.setAttribute('stdDeviation', `${Math.abs(this.position.distanceX / 2)}, 0`)
                    } else {
                        if (position.distanceX * position.distanceY <= 0) {
                            this.angle = 180 - unsortedAngle
                        } else {
                            this.angle = unsortedAngle
                        }
                        this.filter.setAttribute('stdDeviation', `${Math.abs(this.position.distanceY / 2)}, 0`)
                    }
                }
                this.cursor.style.transform += ` rotate(${this.angle}deg)`
                this.previousAngle = this.angle
            }

            stop() {
                setTimeout(() => {
                    this.filter.setAttribute('stdDeviation', '0, 0')
                    this.moving = false
                }, 50)
            }

            hidden() {
                this.cursor.style.opacity = 0
                setTimeout(() => {
                    this.cursor.setAttribute("hidden", "hidden")
                }, 500)
            }
        }

        let cursor = new ArrowPointer()
        document.onmousemove = function (event) {
            cursor.move(event)
        }
        document.ontouchmove = function (event) {
            cursor.move(event.touches[0])
        }
        document.onclick = function () {
            if (typeof cursor.click === 'function') {
                cursor.click()
            }
        }


        
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>
</body>
</html>