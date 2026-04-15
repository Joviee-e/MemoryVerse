# CLAUDE.md — Memory Cosmos: Planet-Based Memory Storage UI

## Project Vision

You are building **Memory Cosmos** — a stunning, immersive 3D space-themed web application where **each planet stores a collection of memories** and **each universe (solar system) represents a contextual group**. Think of it as a cosmic knowledge graph: universes hold context, planets hold memories, and the user navigates through space to explore them.

This is NOT a generic dashboard. This is a **cinematic, awe-inspiring spatial experience** — like flying through an actual universe. Every pixel should feel intentional, every interaction should feel weightful and cosmic.

---

## Architecture & Mental Model

```
Universe (Solar System)
├── Sun (context anchor — the theme/topic of this universe)
├── Planet 1 → Memory cluster (e.g., "Childhood")
│   ├── Memory fragment 1
│   ├── Memory fragment 2
│   └── ...
├── Planet 2 → Memory cluster (e.g., "Travel")
├── Planet 3 → Memory cluster (e.g., "Work Ideas")
└── ... more planets orbiting the sun
```

- **Universe** = A solar system. One part/context of the user's memory. Has a sun at center and planets orbiting.
- **Sun** = The contextual anchor. Represents the overarching theme (e.g., "2024 Projects", "Personal Growth", "Research Notes").
- **Planet** = A memory container. Stores individual memories. Each planet has a unique appearance, size, and orbit.
- **Memory** = A single note/thought/idea stored inside a planet. Revealed when the user clicks/focuses on a planet.

---

## Critical Requirements

### 1. 3D Models — USE THE ACTUAL MODELS
- Check the project folders for `.glb`, `.gltf`, `.obj`, `.fbx` 3D model files for planets, sun, and universe elements.
- **Replace ALL placeholder geometries** (spheres, circles, CSS shapes) with the actual 3D models found in the project.
- Use **Three.js** (`three`), **React Three Fiber** (`@react-three/fiber`), or raw WebGL — whatever fits the stack.
- Load models using `GLTFLoader`, `OBJLoader`, or `FBXLoader` depending on format.
- If no models are found in folders, scan for them recursively: `find . -name "*.glb" -o -name "*.gltf" -o -name "*.obj" -o -name "*.fbx"`
- If models truly don't exist yet, create compelling procedural planets with custom shaders (atmosphere glow, surface noise, ring systems) — but **prioritize real models first**.

### 2. Planet Rotation — NON-NEGOTIABLE
- **Every planet MUST rotate on its own axis continuously** — never stop spinning.
- Each planet should have a **unique rotation speed and axis tilt** for visual variety.
- Planets must also **orbit the sun** at different speeds and distances (Keplerian-style elliptical orbits preferred).
- The sun should have a slow, majestic rotation with emissive glow/corona effect.
- Use `requestAnimationFrame` or the Three.js render loop — no CSS hacks for 3D rotation.

### 3. Space Aesthetic — Go ALL IN
- **Background**: Deep space skybox or particle starfield — not a flat dark color. Use a procedural starfield with depth layers (near stars bright and twinkling, far stars dim and static). Subtle nebula colors in the distance.
- **Lighting**: Dramatic point light from the sun casting realistic shadows on planets. Subtle ambient light so shadow sides aren't pure black. Rim lighting on planets for that cinematic NASA look.
- **Particles**: Floating dust, asteroid debris, or cosmic particles drifting slowly. These add life to empty space.
- **Post-processing**: Bloom/glow on the sun and emissive elements. Optional chromatic aberration for cinematic feel. Subtle vignette on edges.
- **Depth of field**: Planets further from camera slightly blurred (optional but stunning if done right).

### 4. Typography — Premium, Not Generic

**BANNED fonts**: Inter, Roboto, Arial, system-ui, sans-serif defaults. Do NOT use these.

**Required approach**:
- Import from Google Fonts or similar CDN.
- **Display / Headers**: Use something cinematic and futuristic — examples to consider (pick ONE, or find something better):
  - `Orbitron` — geometric, techy, space-native
  - `Exo 2` — clean futuristic with personality
  - `Rajdhani` — sharp, modern, unique
  - `Michroma` — wide, bold, commanding
  - `Audiowide` — retro-futuristic, confident
  - `Oxanium` — rounded futuristic, friendly yet techy
- **Body / UI text**: Pair with a highly legible but characterful font:
  - `Space Mono` — monospace with personality
  - `IBM Plex Sans` — technical precision
  - `Outfit` — geometric, modern, excellent readability
  - `Sora` — geometric humanist, feels polished
- **Font sizing**: Use `clamp()` for responsive type. Headlines should be BOLD and BIG when needed. Don't be timid with scale.
- **Letter spacing**: Add generous tracking to uppercase labels (e.g., `letter-spacing: 0.15em`).
- **Font weight contrast**: Mix thin (300) and bold (700+) weights for visual hierarchy.

### 5. UI Layout & Navigation

**Main Views**:

1. **Universe View (Home)** — Zoomed-out view showing the full solar system. All planets orbiting. Sun glowing at center. User can see the whole system. Hovering a planet shows its name and memory count.

2. **Planet Focus View** — Click a planet → camera smoothly flies toward it → planet fills the view → memories panel slides in from the side showing stored memories. Planet continues rotating in background, slightly blurred.

3. **Memory View** — Individual memory card/modal. Clean, readable. Shows the memory content with metadata (date, tags, etc.).

**Navigation**:
- Smooth camera transitions between views (use GSAP, Tween.js, or Three.js animation).
- Scroll-to-zoom or scroll-to-navigate between planets.
- Keyboard shortcuts: `Esc` to go back, arrow keys to cycle planets.
- A minimal, elegant HUD overlay: universe name, current planet, breadcrumb path.

**HUD / Overlay UI**:
- Semi-transparent panels with `backdrop-filter: blur()` and subtle borders.
- Glass-morphism done RIGHT — not the overused frosted glass everywhere, but strategic use on panels that overlay the 3D scene.
- Subtle grid or scan-line texture on UI panels for a sci-fi control panel feel.
- Status indicators, memory counts, and labels should feel like cockpit instrumentation.

### 6. Color Palette

Design a cohesive cosmic palette. Example direction (adapt as you see fit):

```css
:root {
  /* Deep space */
  --void: #05060f;
  --deep-space: #0a0e1a;
  --nebula-dark: #12152a;
  
  /* Cosmic accents */
  --star-white: #e8eaf6;
  --nebula-blue: #4fc3f7;
  --nebula-purple: #b388ff;
  --solar-gold: #ffd54f;
  --solar-orange: #ff8a65;
  --mars-red: #ef5350;
  --aurora-green: #69f0ae;
  
  /* UI surfaces */
  --panel-bg: rgba(10, 14, 26, 0.85);
  --panel-border: rgba(79, 195, 247, 0.15);
  --text-primary: #e8eaf6;
  --text-secondary: rgba(232, 234, 246, 0.6);
}
```

### 7. Interactions & Micro-animations

- **Planet hover**: Subtle scale-up (1.05x), orbit line highlights, name label fades in.
- **Planet click**: Satisfying camera zoom with slight ease-out bounce.
- **Memory cards**: Staggered fade-in when planet panel opens.
- **Sun pulse**: Gentle breathing glow animation on the sun.
- **Starfield parallax**: Stars shift slightly as user moves mouse (parallax depth).
- **Loading state**: Show a warp-speed star streak animation while content loads.
- **Cursor**: Custom cursor — a small crosshair or dot with trailing glow in the 3D view.

### 8. Responsive Design

- Desktop: Full 3D experience with all effects.
- Tablet: Simplified particles, reduced post-processing, touch-friendly planet selection.
- Mobile: 2.5D or simplified view if full 3D is too heavy. Planets in a scrollable orbit strip. Core functionality must work.

---

## Tech Stack Preferences

Use whatever gets the best result, but here's the recommended stack:

- **3D Engine**: Three.js (or React Three Fiber if React-based)
- **Animation**: GSAP for camera/UI transitions, Three.js clock for rotation loops
- **Framework**: React (preferred) or vanilla JS — your call
- **Styling**: CSS custom properties + scoped styles. Tailwind OK if it doesn't limit creativity.
- **Build**: Vite preferred for speed
- **Model Loading**: `@react-three/drei` (useGLTF, OrbitControls, Stars, etc.) if using R3F

---

## File Structure to Scan

Before building, scan the project for existing assets:

```bash
# Find 3D models
find . -name "*.glb" -o -name "*.gltf" -o -name "*.obj" -o -name "*.fbx" 2>/dev/null

# Find textures
find . -name "*.png" -o -name "*.jpg" -o -name "*.hdr" -o -name "*.exr" 2>/dev/null

# Find existing components
find . -name "*.jsx" -o -name "*.tsx" -o -name "*.vue" 2>/dev/null
```

Use whatever you find. Integrate existing assets before creating new ones.

---

## Data Shape (for mock data / API contract)

```typescript
interface Memory {
  id: string;
  content: string;
  createdAt: Date;
  tags: string[];
  mood?: string; // optional: "calm" | "excited" | "reflective" | ...
}

interface Planet {
  id: string;
  name: string;
  modelPath?: string; // path to .glb/.gltf if available
  color: string; // fallback color if no model
  orbitRadius: number;
  orbitSpeed: number;
  rotationSpeed: number;
  axisTilt: number; // degrees
  size: number;
  memories: Memory[];
}

interface Universe {
  id: string;
  name: string;
  description: string;
  sun: {
    modelPath?: string;
    color: string;
    intensity: number;
  };
  planets: Planet[];
}
```

Populate with realistic mock data — at least 2 universes, 5-8 planets each, 3-10 memories per planet.

---

## What "Done" Looks Like

- [ ] Opening the app feels like launching into space — there's a moment of awe.
- [ ] All planets are ALWAYS rotating — axis spin + orbital motion.
- [ ] 3D models from the project folders are loaded and displayed (not placeholder spheres).
- [ ] Typography is premium, consistent, and readable against the dark space background.
- [ ] Clicking a planet smoothly transitions the camera and reveals its memories.
- [ ] The starfield/background has depth and subtle animation.
- [ ] The sun glows with bloom/emissive light.
- [ ] UI panels feel like sci-fi cockpit instruments, not generic cards.
- [ ] The whole experience is performant (60fps on modern hardware).
- [ ] Mobile has a functional, graceful fallback.

---

## Creative Freedom

You have full creative freedom on:
- Exact visual style within the space theme
- Animation choreography and timing
- Layout composition and panel design
- Additional atmospheric effects (nebulae, asteroids, comets, rings)
- Sound design (optional: ambient space hum, click sounds)
- Easter eggs (optional: shooting stars, hidden constellation when idle)
- Additional views or features that enhance the cosmic memory experience

The only hard rules: **planets rotate**, **use the 3D models**, **typography is premium**, **it looks professional and cinematic**.

---

*Build something that makes people forget they're looking at a web browser.*
