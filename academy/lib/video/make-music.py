"""
Original background music for the Eltech Academy intro videos (royalty-free, made from code).

45 seconds, 96 BPM, C - G - Am - F: soft pad, bass, a plucked arpeggio and a light beat,
with a fade in and a calm ending. Needs numpy.

Usage: python make-music.py out.wav
"""
import sys
import wave

import numpy as np

SR = 44100
LENGTH = 45.0
BEAT = 60 / 96
BAR = BEAT * 4

t_all = np.arange(int(SR * LENGTH)) / SR
left = np.zeros_like(t_all)
right = np.zeros_like(t_all)


def freq(midi):
    return 440.0 * 2 ** ((midi - 69) / 12)


def add(signal, start, pan=0.0, gain=1.0):
    """Mix a mono signal into the stereo buffer at a start time (pan -1 left .. 1 right)."""
    i = int(start * SR)
    if i >= len(t_all):
        return
    signal = signal[: len(t_all) - i] * gain
    left[i:i + len(signal)] += signal * (1 - pan) / 2
    right[i:i + len(signal)] += signal * (1 + pan) / 2


def tone(f, dur, harmonics=(1.0,), detune=0.0):
    t = np.arange(int(SR * dur)) / SR
    out = np.zeros_like(t)
    for n, amp in enumerate(harmonics, start=1):
        out += amp * np.sin(2 * np.pi * f * n * (1 + detune) * t)
    return out


def envelope(n, attack, release):
    env = np.ones(n)
    a, r = min(int(attack * SR), n // 2), min(int(release * SR), n // 2)
    if a:
        env[:a] = np.linspace(0, 1, a)
    if r:
        env[-r:] *= np.linspace(1, 0, r)
    return env


# Chords: (bass note, pad notes)
CHORDS = {
    'C': (48, [60, 64, 67, 72]),
    'G': (43, [59, 62, 67, 71]),
    'Am': (45, [60, 64, 69, 72]),
    'F': (41, [60, 65, 69, 72]),
}
PROGRESSION = ['C', 'G', 'Am', 'F'] * 3          # 2 bars each = 24 bars, trimmed to 45 s
bars = int(LENGTH / BAR) + 1

for idx in range(bars):
    name = PROGRESSION[(idx // 2) % len(PROGRESSION)]
    bass, pad = CHORDS[name]
    start = idx * BAR
    if start >= LENGTH - 0.1:
        break
    last = start + BAR > LENGTH - BAR * 2           # final two bars: calm ending on C
    if last:
        bass, pad = CHORDS['C']

    # Pad: two slightly detuned voices per note, soft attack, spread left/right
    if idx % 2 == 0:
        dur = min(BAR * 2 + 0.6, LENGTH - start)
        for k, note in enumerate(pad):
            for det, pan in ((-0.0025, -0.6), (0.0025, 0.6)):
                s = tone(freq(note), dur, (1.0, 0.25, 0.08), det)
                add(s * envelope(len(s), 0.5, 0.9), start, pan, 0.035)

    # Bass: half notes
    for beat in (0, 2):
        s = tone(freq(bass), BEAT * 2, (1.0, 0.35, 0.1))
        tt = np.arange(len(s)) / SR
        add(s * np.exp(-tt * 1.6) * envelope(len(s), 0.01, 0.15), start + beat * BEAT, 0, 0.16)

    # Plucked arpeggio from bar 3, eighth notes, alternating pan
    if 2 <= idx and not last:
        pattern = [0, 1, 2, 3, 2, 1, 2, 3]
        for e, p in enumerate(pattern):
            s = tone(freq(pad[p] + 12), BEAT, (1.0, 0.3, 0.1))
            tt = np.arange(len(s)) / SR
            add(s * np.exp(-tt * 7) * envelope(len(s), 0.004, 0.05), start + e * BEAT / 2, -0.4 if e % 2 else 0.4, 0.05)

    # Light beat from bar 5: soft kick on every beat, shaker on the off-beats
    if 4 <= idx and not last:
        for beat in range(4):
            n = int(0.25 * SR)
            tt = np.arange(n) / SR
            sweep = 2 * np.pi * np.cumsum(45 + 75 * np.exp(-tt * 30)) / SR
            add(np.sin(sweep) * np.exp(-tt * 16), start + beat * BEAT, 0, 0.22)
            noise = np.random.default_rng(idx * 10 + beat).standard_normal(int(0.06 * SR))
            noise = np.diff(noise, prepend=0) * np.exp(-np.arange(len(noise)) / SR * 60)
            add(noise, start + beat * BEAT + BEAT / 2, 0.3, 0.018)

# Fade in and out, then normalise to -3 dBFS
fade = np.ones_like(t_all)
fade[t_all < 1.0] = t_all[t_all < 1.0]
tail = t_all > LENGTH - 3.0
fade[tail] = (LENGTH - t_all[tail]) / 3.0
stereo = np.stack([left * fade, right * fade], axis=1)
stereo *= 0.7 / np.max(np.abs(stereo))

with wave.open(sys.argv[1], 'wb') as w:
    w.setnchannels(2)
    w.setsampwidth(2)
    w.setframerate(SR)
    w.writeframes((stereo * 32767).astype(np.int16).tobytes())
print('wrote', sys.argv[1])
