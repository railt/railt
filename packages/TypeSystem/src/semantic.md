
## Type System

### Type Definition

- [x] There can be only one type named "%s"

### Type Usage

- [x] Type "%s" not found and could not be loaded

### Directive Definition

- [x] Trying to define directive %s, but %s location is invalid

### Directive Usage

- [ ] Trying to define directive %s on %s, but only %s locations allowed
- [x] Directive "@%s" not found and could not be loaded
- [ ] Argument "%s" not defined in %s

### Argument Definition

- [ ] Non-Null %s can not be initialized by NULL
- [ ] Non-List %s can not be initialized by List %s
- [ ] %s defined by %s can not be initialized by %s

### Argument Usage

- [ ] Argument %s not defined in %s
- [ ] %s must be type of Scalar, Enum or Input
- [ ] The argument %s of %s passes non compatible value %s
- [ ] The argument %s of %s should contain list value, but %s given
- [ ] Required argument "%s" of %s not specified
- [ ] In the %s there is no specified argument "%s"

### Schema Definition

- [ ] The %s must contain an Object type reference to the "%s" field 

### Enum Definition

- [ ] %s can not be empty

### Object Definition

- [ ] Only interface can be implemented by the %s, but %s given
- [ ] %s must contain the remaining %s of the %s
- [ ] The %s of the %s contains an argument %s, but the %s does not implement it

### Field Definition

- [ ] Can not redefine already defined %s

### LSP Overloading

- [ ] %s definition of %s must be a compatible %s, but %s given
- [ ] %s in %s definition must be instance of %s
- [ ] Scalar %s of %s does not compatible with %s of %s
- [ ] The %s cannot be overridden by non-list, but %s given
- [ ] %s postcondition of %s can not be weakened by %s of %s
- [ ] %s precondition of %s can not be strengthened by %s of %s
